<?php

namespace Insidestyles\JsonRpcBundle;

use Insidestyles\JsonRpcBundle\DependencyInjection\Compiler\AddProcessorsPass;
use Insidestyles\JsonRpcBundle\DependencyInjection\JsonRpcExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author Fuong <insidestyles@gmail.com>
 */
class JsonRpcBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function boot()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new AddProcessorsPass());
    }

    /**
     * {@inheritdoc}
     */
    public function getContainerExtension()
    {
        return new JsonRpcExtension();
    }
}
