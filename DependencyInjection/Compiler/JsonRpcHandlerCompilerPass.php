<?php

namespace Insidestyles\JsonRpcBundle\DependencyInjection\Compiler;

use Insidestyles\JsonRpcBundle\DependencyInjection\JsonRpcExtension;
use Insidestyles\JsonRpcBundle\Sdk\Contract\JsonRpcApiInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * @author Fuong <insidestyles@gmail.com>
 */
class JsonRpcHandlerCompilerPass implements CompilerPassInterface
{
    private $apiTag;

    public function __construct()
    {
        $this->apiTag = JsonRpcExtension::ALIAS;
    }

    public function process(ContainerBuilder $container)
    {
        $rootServerPath = JsonRpcExtension::ALIAS . '.server.';

        $taggedServices = $container->findTaggedServiceIds($this->apiTag);

        foreach ($taggedServices as $id => $tags) {
            $handler = '';
            foreach ($tags as $attributes) {
                if (empty($attributes['handler'])) {
                    throw new \RuntimeException('Missing required attribute "handler".');
                }
                $handler = $attributes['handler'];
            }
            $implementedInterfaces = class_implements($container->findDefinition($id)->getClass());

            $serverDefinition = $container->findDefinition($rootServerPath . $handler);

            foreach ($implementedInterfaces as $implementedInterface) {
                if ($implementedInterface == JsonRpcApiInterface::class) {
                    $serverDefinition->addMethodCall('setClass', [$container->findDefinition($id), $implementedInterface]);
                }
            }
        }
    }
}