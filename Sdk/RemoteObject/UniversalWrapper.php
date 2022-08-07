<?php
/**
 * User: insidestyles
 * Date: 21.09.2019
 * Time: 15:59
 */

namespace Insidestyles\JsonRpcBundle\Sdk\RemoteObject;

use Insidestyles\JsonRpcBundle\Exception\MethodNotFoundException;
use Insidestyles\JsonRpcBundle\Exception\RemoteObjectCallException;
use Insidestyles\JsonRpcBundle\Server\Adapter\Serializer\SerializerInterface;
use ProxyManager\Factory\RemoteObject\Adapter\JsonRpc;
use ProxyManager\Factory\RemoteObjectFactory;
use Laminas\Http\Client as HttpClient;
use Laminas\Json\Server\Client as ServerClient;
use ProxyManager\Proxy\RemoteObjectInterface;

/**
 * @author Fuong <insidestyles@gmail.com>
 */
class UniversalWrapper implements RemoteObjectWrapperInterface
{
    private HttpClient $httpClient;

    private RemoteObjectInterface $remoteObject;

    private RemoteObjectFactory $remoteObjectFactory;

    public function __construct(
        HttpClient $httpClient,
        string $jsonRpcUrl,
        string $serviceInterface,
        array $serviceMaps = [],
        ?SerializerInterface $serializer = null
    ) {
        $this->httpClient = $httpClient;
        $this->remoteObjectFactory = new RemoteObjectFactory(
            new JsonRpc(
                new ServerClient($jsonRpcUrl, $this->httpClient),
                $serviceMaps
            )
        );
        $this->remoteObject = $this->remoteObjectFactory->createProxy($serviceInterface);
    }

    public function __call($name, $arguments): mixed
    {
        if (method_exists($this->remoteObject, $name)) {
            try {
                return $this->remoteObject->$name(...$arguments);
            } catch (\Throwable $e) {
                throw new RemoteObjectCallException($e->getMessage());
            }
        }

        throw new MethodNotFoundException();
    }

    public function setHttpHeaders(array $headers): void
    {
        $this->httpClient->setHeaders($headers);
    }

    public function setOptions(array $options): void
    {
        $this->httpClient->setOptions($options);
    }
}
