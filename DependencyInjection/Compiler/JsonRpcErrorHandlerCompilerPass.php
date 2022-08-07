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
    private string $config;
    private string $handlerTag;

    public function __construct()
    {
        $this->config = JsonRpcExtension::ALIAS;
        $this->handlerTag = JsonRpcExtension::ERROR_HANDLER_TAG;
    }

    public function process(ContainerBuilder $container): void
    {
        $handlerRegistryDefinition = $container->getDefinition($this->config . '.error_handler.manager');
        $services = $container->findTaggedServiceIds($this->handlerTag);

        foreach ($services as $id => $tags) {
            $handlerRegistryDefinition->addMethodCall('addHandler', [$container->getDefinition($id),]);
        }
    }
}