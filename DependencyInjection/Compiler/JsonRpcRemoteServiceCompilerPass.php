<?php

namespace Insidestyles\JsonRpcBundle\DependencyInjection\Compiler;

use Insidestyles\JsonRpcBundle\Annotation\JsonRpcApi;
use Insidestyles\JsonRpcBundle\DependencyInjection\JsonRpcExtension;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;

/**
 * @author Fuong <insidestyles@gmail.com>
 */
class JsonRpcRemoteServiceCompilerPass implements CompilerPassInterface
{
    private $remoteServiceTag;

    public function __construct()
    {
        $this->remoteServiceTag = JsonRpcExtension::REMOTE_SERVICE_TAG;
    }

    public function process(ContainerBuilder $container)
    {
        try {
            $reader = $container->get('annotation_reader');
        } catch (ServiceNotFoundException $e) {
            $reader = null;
        }
        $taggedServices = $container->findTaggedServiceIds($this->remoteServiceTag);

        foreach ($taggedServices as $id => $tags) {
            foreach ($tags as $attributes) {
                if (empty($attributes['url']) || empty($attributes['class'])) {
                    throw new \RuntimeException('Missing required attribute "url".');
                }
                $url = $attributes['url'];
                $class = $attributes['class'];
                $serviceMaps = [];
                if ($reader) {
                    $reflection = new \ReflectionClass($class);
                    $annotation = $reader->getClassAnnotation($reflection, JsonRpcApi::class);
                    if ($annotation) {
                        $methods = $reflection->getMethods(\ReflectionMethod::IS_PUBLIC);
                        foreach ($methods as $method) {
                            $serviceMaps[$class . '.' . $method->getName()] = $annotation->namespace . '.' . $method->getName();
                        }
                    }
                }
                $httpClient = new ChildDefinition('json_rpc_api.client.zend_http_client_abstract');
                $remoteServiceDefinition = new ChildDefinition('json_rpc_api.remote_service.universal_wrapper_abstract');
                $remoteServiceDefinition->replaceArgument(0, $httpClient);
                $remoteServiceDefinition->replaceArgument(1, $url);
                $remoteServiceDefinition->replaceArgument(2, $class);
                $remoteServiceDefinition->replaceArgument(3, $serviceMaps);
                $container->setDefinition($id, $remoteServiceDefinition);
            }
        }
    }
}
