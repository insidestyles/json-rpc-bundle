<?php

namespace Insidestyles\JsonRpcBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * @author Fuong <insidestyles@gmail.com>
 */
class JsonRpcExtension extends Extension
{
    const ALIAS = 'json_rpc_api';

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
            $container->getDefinition('json_rpc_api.routing.loader')->addMethodCall('addPath', [
                $handlerKey,
                $handlerInfo['host'],
                $handlerInfo['path'],
            ]);
        }
    }

    /**
     * Register handler instance
     */
    private function registerHandler(string $handlerKey, array $handlerInfo, ContainerBuilder $container)
    {
        $rootHandlerPath = self::ALIAS . '.handler.';
        $rootServerPath = self::ALIAS . '.server.';
        $handlerId = $rootHandlerPath . $handlerKey;
        $serverId = $rootServerPath . $handlerKey;

        $definitions = [
            'server' => [
                'zend-json-rpc' => $rootServerPath . 'json_rpc_abstract',
            ],
            'handler' => [
                'zend-json-rpc' => $rootHandlerPath . 'zend_json_rpc_abstract',
            ],
        ];

        $handlerDefinition = new ChildDefinition($definitions['handler']['zend-json-rpc']);

        $serverDefinition = new ChildDefinition($definitions['server']['zend-json-rpc']);
        $container->setDefinition($serverId, $serverDefinition);

        $handlerDefinition->replaceArgument(0, $serverDefinition);

        if (!empty($handlerInfo['logger'])) {
            $logger = $container->getDefinition($handlerInfo['logger']);
            $handlerDefinition->replaceArgument(1, $logger);
        }

        if (!empty($handlerInfo['serializer'])) {
            $serializer = $container->getDefinition($handlerInfo['serializer']);
            $handlerDefinition->replaceArgument(2, $serializer);
        }

        $container->setDefinition($handlerId, $handlerDefinition);

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
