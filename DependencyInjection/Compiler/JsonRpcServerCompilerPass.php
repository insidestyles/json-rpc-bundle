<?php

namespace Insidestyles\JsonRpcBundle\DependencyInjection\Compiler;

use Insidestyles\JsonRpcBundle\DependencyInjection\JsonRpcExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * @author Fuong <insidestyles@gmail.com>
 */
class JsonRpcServerCompilerPass implements CompilerPassInterface
{
    private $config;
    private $handlerTag;

    public function __construct()
    {
        $this->config = JsonRpcExtension::ALIAS;
        $this->handlerTag = JsonRpcExtension::ALIAS . '_handler';
    }

    public function process(ContainerBuilder $container)
    {
        $handlerRegistryDefinition = $container->getDefinition($this->config . '.handler.locator');
        $services = $container->findTaggedServiceIds($this->handlerTag);

        foreach ($services as $id => $tags) {
            foreach ($tags as $attributes) {
                $key = $attributes['key'];
            }
            $handlerRegistryDefinition->addMethodCall('addHandler', [
                $container->getDefinition($id),
                $key,
            ]);
        }
    }
}