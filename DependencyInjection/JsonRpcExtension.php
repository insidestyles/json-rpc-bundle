<?php

namespace Insidestyles\JsonRpcBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * @author Fuong <insidestyles@gmail.com>
 */
class JsonRpcExtension extends Extension
{
    const ALIAS = 'json_rpc_server';

    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);
        if (isset($config['handlers'])) {
            $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
            $loader->load('json-rpc.yaml');
        }
        // Compile all handlers
        foreach ($config['handlers'] as $handlerKey => $handlerInfo) {
            // Register handler
            $this->registerHandler($handlerKey, $handlerInfo, $container);

            // Add info to routing loader
            $container->getDefinition('api.routing_loader')->addMethodCall('addPath', [
                $handlerKey,
                $handlerInfo['host'],
                $handlerInfo['path']
            ]);
        }
    }

    /**
     * Register handler instance
     */
    private function registerHandler($handlerKey, array $handlerInfo, ContainerBuilder $container)
    {
        $handlerId = self::ALIAS . '.handler.' . $handlerKey;

        return $handlerId;
    }

    /**
     * {@inheritDoc}
     */
    public function getAlias()
    {
        return self::ALIAS;
    }
}
