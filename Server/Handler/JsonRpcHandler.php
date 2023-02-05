<?php

namespace Insidestyles\JsonRpcBundle\Server\Handler;

use Insidestyles\JsonRpcBundle\Exception\Errors;
use Insidestyles\JsonRpcBundle\Server\Adapter\Serializer\DefaultSerializer;
use Insidestyles\JsonRpcBundle\Server\Adapter\Serializer\DefaultSerializerContext;
use Insidestyles\JsonRpcBundle\Server\Adapter\Serializer\SerializerContextInterface;
use Insidestyles\JsonRpcBundle\Server\Adapter\Serializer\SerializerInterface;
use Insidestyles\JsonRpcBundle\Server\ErrorHandler\ErrorHandlerManager;
use Insidestyles\JsonRpcBundle\Server\ErrorHandler\ErrorHandlerManagerInterface;
use JetBrains\PhpStorm\Pure;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Laminas\Json\Server\Server;
use Laminas\Json\Server\Request as JsonRpcRequest;
use Laminas\Json\Server\Response as JsonRpcResponse;

/**
 * @author Fuong <insidestyles@gmail.com>
 */
class JsonRpcHandler implements JsonRpcHandlerInterface
{
    #[Pure]
    public function __construct(private readonly Server $server, private readonly LoggerInterface $logger = new NullLogger(), private readonly SerializerInterface $serializer = new DefaultSerializer(), private readonly SerializerContextInterface $serializerContext = new DefaultSerializerContext(), private readonly ErrorHandlerManagerInterface $errorHandler = new ErrorHandlerManager())
    {
    }

    public function handle(Request $request): Response
    {
        $this->server->setReturnResponse(true);
        if ('GET' == $request->getMethod()) {
            return (new Response($this->server->getServiceMap(), Response::HTTP_OK, ['Content-Type' => 'application/json']));
        }
        $content = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        if (!empty($content[0])) {
            $response = [];
            foreach ($content as $childContent) {
                $response[] = $this->handleRequest(json_encode($childContent, JSON_THROW_ON_ERROR));
            }
        } else {
            $response = $this->handleRequest($request->getContent());
        }

        return new Response(
            $this->serializer->serialize($response, $this->serializerContext),
            Response::HTTP_OK,
            ['Content-Type' => 'application/json']
        );
    }

    private function handleRequest(string $jsonContent): array
    {
        $response = [];
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
                $response['error'] = $this->handleErrorResponse($jsonServerResponse);
            } else {
                $response['result'] = $jsonServerResponse->getResult();
            }
        } catch (\Throwable $e) {
            $this->logger->critical($e->getMessage());
            $response['id'] = null;
            $response['error'] = [
                'code' => Errors::INTERNAL_ERROR,
                'message' => 'Internal Error',
                'data' => [],
            ];
        }

        return $response;
    }

    private function handleErrorResponse(JsonRpcResponse $jsonServerResponse): array
    {
        $errors = $jsonServerResponse->getError()->toArray();
        $errorObject = $errors['data'];
        $this->logger->error($errors['message']);

        return $this->errorHandler->handle($errorObject);
    }
}
