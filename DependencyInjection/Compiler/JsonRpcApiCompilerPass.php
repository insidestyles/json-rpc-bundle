<?php

namespace Insidestyles\JsonRpcBundle\DependencyInjection\Compiler;

use Insidestyles\JsonRpcBundle\Annotation\JsonRpcApi;
use Insidestyles\JsonRpcBundle\DependencyInjection\JsonRpcExtension;
use Insidestyles\JsonRpcBundle\Sdk\Contract\JsonRpcApiInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;

/**
 * @author Fuong <insidestyles@gmail.com>
 */
class JsonRpcApiCompilerPass implements CompilerPassInterface
{
    private string $apiTag;

    public function __construct()
    {
        $this->apiTag = JsonRpcExtension::API_TAG;
    }

    public function process(ContainerBuilder $container): void
    {
        try {
            $reader = $container->get('annotation_reader');
        } catch (ServiceNotFoundException $e) {
            $reader = null;
        }

        $taggedServices = $container->findTaggedServiceIds($this->apiTag);

        foreach ($taggedServices as $id => $tags) {
            foreach ($tags as $attributes) {
                if (empty($attributes['handler'])) {
                    throw new \RuntimeException('Missing required attribute "handler".');
                }
                $handler = $attributes['handler'];
                $serverDefinition = $container->findDefinition(JsonRpcExtension::SERVER_ID_PREFIX . $handler);
                $handlerDefinition = $container->findDefinition(JsonRpcExtension::HANDLER_ID_PREFIX . $handler);
                $enableAnnotation = $handlerDefinition->getArgument(4);
                $implementedInterfaces = class_implements($container->findDefinition($id)->getClass());
                foreach ($implementedInterfaces as $implementedInterface) {
                    if (is_subclass_of($implementedInterface, JsonRpcApiInterface::class)) {
                        if ($reader && $enableAnnotation) {
                            $reflection = new \ReflectionClass($implementedInterface);
                            $annotation = $reader->getClassAnnotation($reflection, JsonRpcApi::class);
                            if ($annotation) {
                                $methods = $reflection->getMethods(\ReflectionMethod::IS_PUBLIC);
                                foreach ($methods as $method) {
                                    $serverDefinition->addMethodCall('addFunction', [[$container->findDefinition($id), $method->getName()], $annotation->namespace,]);
                                }
                            }
                        } else {
                            $serverDefinition->addMethodCall('setClass', [$container->findDefinition($id), $implementedInterface]);
                        }
                    }
                }
            }
        }
    }
}
