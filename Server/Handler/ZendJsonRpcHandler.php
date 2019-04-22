<?php

namespace Insidestyles\JsonRpcBundle\Server\Handler;

use Insidestyles\JsonRpcBundle\Exception\Errors;
use Insidestyles\JsonRpcBundle\Exception\InternalException;
use Insidestyles\JsonRpcBundle\Server\Adapter\Serializer\DefaultSerializer;
use Insidestyles\JsonRpcBundle\Server\Adapter\Serializer\DefaultSerializerContext;
use Insidestyles\JsonRpcBundle\Server\Adapter\Serializer\SerializerContextInterface;
use Insidestyles\JsonRpcBundle\Server\Adapter\Serializer\SerializerInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Exception\ValidationFailedException;
use Zend\Json\Server\Server;
use Zend\Json\Server\Request as JsonRpcRequest;

/**
 * @author Fuong <insidestyles@gmail.com>
 */
class ZendJsonRpcHandler implements JsonRpcHandlerInterface
{
    private $server;
    private $logger;
    private $serializer;
    private $serializerContext;
    private $enableAnnotation;

    public function __construct(Server $server, ?LoggerInterface $logger = null, ?SerializerInterface $serializer = null, ?SerializerContextInterface $serializerContext = null, ?bool $enableAnnotation = false)
    {
        $this->server = $server;
        $this->logger = $logger ?? new NullLogger();
        $this->serializer = $serializer ?? new DefaultSerializer();
        $this->serializerContext = $serializerContext ?? new DefaultSerializerContext();
        $this->enableAnnotation = $enableAnnotation;
    }

    public function handle(Request $request): Response
    {
        $this->server->setReturnResponse(true);
        if ('GET' == $request->getMethod()) {

            return (new Response($this->server->getServiceMap(), 200, ['Content-Type' => 'application/json']));
        }
        $content = json_decode($request->getContent(), true);
        if (!empty($content[0])) {
            $response = [];
            foreach ($content as $childContent) {
                $response[] = $this->handlRequest($childContent);
            }
        } else {
            $response = $this->handlRequest($request->getContent());
        }

        return new Response($this->serializer->serialize($response, $this->serializerContext), 200, ['Content-Type' => 'application/json']);
    }

    private function handlRequest(string $jsonContent)
    {
        $rpcRequest = new JsonRpcRequest();
        $rpcRequest->loadJson($jsonContent);

        if ($rpcRequest->getId() === null) {
            $rpcRequest->setId(uniqid());
        }

        try {
            $jsonServerResponse = $this->server->handle($rpcRequest);
            $response = [
                'jsonrpc' => $jsonServerResponse->getVersion(),
                'id' => $jsonServerResponse->getId(),
            ];


            if ($jsonServerResponse->isError()) {
                $errors = $jsonServerResponse->getError()->toArray();
                $errorObject = $errors['data'];
                if ($errorObject instanceof ValidationFailedException) {
                    $response['error'] = [
                        'code' => Errors::VALIDATION_ERROR,
                        'message' => 'Validation Error',
                        'data' => $errorObject->getViolations(),
                    ];
                } else {
                    $errors['data'] = [];
                }
                $response['error'] = $errors;
            } else {
                $response['result'] = $jsonServerResponse->getResult();
            }
        } catch (\Throwable $e) {
            $this->logger->error($e->getMessage());
            $response['id'] = null;
            $response['error'] = [
                'code' => Errors::INTERNAL_ERROR,
                'message' => $e->getMessage(),
                'data' => new InternalException($e->getMessage()),
            ];
        }

        return $response;
    }
}
