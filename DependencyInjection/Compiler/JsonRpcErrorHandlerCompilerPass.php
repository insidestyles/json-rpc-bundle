<?php

namespace Insidestyles\JsonRpcBundle\DependencyInjection\Compiler;

use Insidestyles\JsonRpcBundle\DependencyInjection\JsonRpcExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * @author Fuong <insidestyles@gmail.com>
 */
class JsonRpcErrorHandlerCompilerPass implements CompilerPassInterface
{
    public function __construct(
        private readonly string $config = JsonRpcExtension::ALIAS,
        private readonly string $handlerTag = JsonRpcExtension::ERROR_HANDLER_TAG,
    ) {
    }

    public function process(
        ContainerBuilder $container
    ): void {
        $handlerRegistryDefinition = $container->getDefinition($this->config . '.error_handler.manager');
        $services = $container->findTaggedServiceIds($this->handlerTag);

        foreach ($services as $id => $tags) {
            $handlerRegistryDefinition->addMethodCall('addHandler', [$container->getDefinition($id),]);
        }
    }
}
