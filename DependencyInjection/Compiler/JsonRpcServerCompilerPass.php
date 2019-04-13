<?php

namespace Insidestyles\JsonRpcBundle\DependencyInjection\Compiler;

use Insidestyles\JsonRpcBundle\Exception\InternalException;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * @author Fuong <insidestyles@gmail.com>
 */
class JsonRpcServerCompilerPass implements CompilerPassInterface
{
    public function process(
        ContainerBuilder $container
    ) {
        if (!$container->has('json_rpc_server')) {
            return;
        }

        $definition = $container->findDefinition(
            'json_rpc_server'
        );

        $taggedServices = $container->findTaggedServiceIds(
            'json_rpc_api'
        );

        foreach ($taggedServices as $id => $tags) {
            $implementedInterfaces = class_implements($container->findDefinition($id)->getClass());

            if (empty($implementedInterfaces)) {
                throw new InternalException(sprintf('The JSON-RPC Api class %s MUST implement at least one interface to be accessible with api-sdk!', $container->findDefinition($id)->getClass()));
            }

            //now, we are assuming that the first of the implemented interfaces is the one, we need.
            $jsonRpcNamespace = array_shift($implementedInterfaces);

            $definition->addMethodCall('setClass', [$container->findDefinition($id), $jsonRpcNamespace]);
        }
    }
}