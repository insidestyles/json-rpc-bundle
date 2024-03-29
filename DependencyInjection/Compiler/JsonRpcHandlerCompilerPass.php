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
    public function __construct(
        private readonly string $config = JsonRpcExtension::ALIAS,
        private readonly string $handlerTag = JsonRpcExtension::HANDLER_TAG,
    ) {
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
