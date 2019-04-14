<?php

namespace Insidestyles\JsonRpcBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * @author Fuong <insidestyles@gmail.com>
 */
class AddProcessorsPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('json_rpc_api.handlers')) {
            return;
        }
    }
}
