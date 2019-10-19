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
use Zend\Http\Client as HttpClient;
use Zend\Json\Server\Client as ServerClient;

/**
 * @author Fuong <insidestyles@gmail.com>
 */
class UniversalWrapper implements RemoteObjectWrapperInterface
{
    private $httpClient;

    private $remoteObject;

    private $remoteObjectFactory;

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

    public function __call($name, $arguments)
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

    public function setHttpHeaders(array $headers)
    {
        $this->httpClient->setHeaders($headers);
    }

    public function setOptions(array $options)
    {
        $this->httpClient->setOptions($options);
    }
}
