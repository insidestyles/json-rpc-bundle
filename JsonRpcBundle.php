<?php

namespace Insidestyles\JsonRpcBundle;

use Insidestyles\JsonRpcBundle\DependencyInjection\Compiler\JsonRpcApiCompilerPass;
use Insidestyles\JsonRpcBundle\DependencyInjection\Compiler\JsonRpcErrorHandlerCompilerPass;
use Insidestyles\JsonRpcBundle\DependencyInjection\Compiler\JsonRpcHandlerCompilerPass;
use Insidestyles\JsonRpcBundle\DependencyInjection\Compiler\JsonRpcRemoteServiceCompilerPass;
use Insidestyles\JsonRpcBundle\DependencyInjection\JsonRpcExtension;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
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
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);
        $container->addCompilerPass(new JsonRpcApiCompilerPass());
        $container->addCompilerPass(new JsonRpcHandlerCompilerPass());
        $container->addCompilerPass(new JsonRpcErrorHandlerCompilerPass());
        $container->addCompilerPass(new JsonRpcRemoteServiceCompilerPass());
    }

    /**
     * {@inheritdoc}
     */
    #[Pure]
    public function getContainerExtension(): ?ExtensionInterface
    {
        return new JsonRpcExtension();
    }
}
