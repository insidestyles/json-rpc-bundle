<?php

namespace Insidestyles\JsonRpcBundle\Server\Handler;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Exception\ValidationFailedException;
use Zend\Json\Server\Server;
use Zend\Json\Server\Smd;
use Zend\Json\Server\Request as JsonRpcRequest;

/**
 * @author Fuong <insidestyles@gmail.com>
 */
class JsonRpcServer implements JsonRpcServerInterface
{
    /**
     * @var Server
     */
    private $server;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var SerializationContextFactoryInterface
     */
    private $contextFactory;

    /**
     * JsonRpcServer constructor.
     * @param Server $server
     * @param SerializerInterface $serializer
     * @param SerializationContextFactoryInterface $contextFactory
     */
    public function __construct(
        Server $server,
        SerializerInterface $serializer,
        SerializationContextFactoryInterface $contextFactory
    ) {
        $this->server = $server;
        $this->serializer = $serializer;
        $this->contextFactory = $contextFactory;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function handle(Request $request)
    {
        $this->server->setReturnResponse(true);
        if ('GET' == $request->getMethod()) {
            // Indicate the URL endpoint, and the JSON-RPC version used:
            $this->server->setTarget('/api')
                ->setEnvelope(Smd::ENV_JSONRPC_2);

            return (new Response($this->server->getServiceMap(), 200, ['Content-Type' => 'application/json']));
        }

        $rpcRequest = new JsonRpcRequest();
        $rpcRequest->loadJson($request->getContent());

        if ($rpcRequest->getId() === null) {
            $rpcRequest->setId(uniqid());
        }

        try {
            $jsonServerResponse = $this->server->handle($rpcRequest);
            $response = ['id' => $jsonServerResponse->getId()];

            if ($jsonServerResponse->isError()) {
                $errors = $jsonServerResponse->getError()->toArray();
                $errorObject = $errors['data'];
                if ($errorObject instanceof ValidationFailedException) {
                    $errors['message'] = 'Validation Error';
                    $errors['data'] = $errorObject->getViolations();
                } else {
                    $errors['data'] = [];//hide
                }
                $response['error'] = $errors;
            } else {
                $response['result'] = $jsonServerResponse->getResult();
            }

            if (null !== ($version = $jsonServerResponse->getVersion())) {
                $response['jsonrpc'] = $version;
            }
        } catch (\Throwable $e) {
            $response['id'] = null;
            $response['error'] = [
                'code' => -32099,
                'message' => $e->getMessage(),
                'data' => new InternalException($e->getMessage())
            ];
        }

        $context = $this->contextFactory->createSerializationContext();

        return new Response($this->serializer->serialize($response, 'json', $context), 200,
            ['Content-Type' => 'application/json']);
    }
}
