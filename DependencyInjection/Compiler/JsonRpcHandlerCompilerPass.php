<?php

namespace Insidestyles\JsonRpcBundle\DependencyInjection\Compiler;

use Insidestyles\JsonRpcBundle\DependencyInjection\JsonRpcExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * @author Fuong <insidestyles@gmail.com>
 */
class JsonRpcHandlerCompilerPass implements CompilerPassInterface
{
    private string $config;
    private string $handlerTag;

    public function __construct()
    {
        $this->config = JsonRpcExtension::ALIAS;
        $this->handlerTag = JsonRpcExtension::HANDLER_TAG;
    }

    public function process(ContainerBuilder $container): void
    {
        $handlerRegistryDefinition = $container->getDefinition($this->config . '.handler.locator');
        $services = $container->findTaggedServiceIds($this->handlerTag);

        foreach ($services as $id => $tags) {
            foreach ($tags as $attributes) {
                $key = $attributes['key'];
            }
            $handlerRegistryDefinition->addMethodCall('addHandler', [$container->getDefinition($id), $key,]);
        }
    }
}