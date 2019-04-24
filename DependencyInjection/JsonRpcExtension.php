<?php

namespace Insidestyles\JsonRpcBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Zend\Json\Server\Smd;

/**
 * @author Fuong <insidestyles@gmail.com>
 */
class JsonRpcExtension extends Extension
{
    const ALIAS = 'json_rpc_api';
    const SERVER_ID_PREFIX = JsonRpcExtension::ALIAS . '.server.';
    const HANDLER_ID_PREFIX = JsonRpcExtension::ALIAS . '.handler.';
    const HANDLER_TAG = 'json_rpc_api_handler';
    const API_TAG = 'json_rpc_api';

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
        $handlerId = self::HANDLER_ID_PREFIX . $handlerKey;
        $serverId = self::SERVER_ID_PREFIX . $handlerKey;

        $definitions = [
            'server' => [
                'zend-json-rpc' => self::SERVER_ID_PREFIX . 'json_rpc_abstract',
            ],
            'handler' => [
                'zend-json-rpc' => self::HANDLER_ID_PREFIX . 'zend_json_rpc_abstract',
            ],
        ];

        $handlerDefinition = new ChildDefinition($definitions['handler']['zend-json-rpc']);

        $serverDefinition = new ChildDefinition($definitions['server']['zend-json-rpc']);
        $serverDefinition->addMethodCall('setTarget', [$handlerKey,]);
        $serverDefinition->addMethodCall('setDescription', [$handlerKey . ' Api',]);
        $serverDefinition->addMethodCall('setEnvelope', [Smd::ENV_JSONRPC_2,]);
        $container->setDefinition($serverId, $serverDefinition);

        $handlerDefinition->replaceArgument(0, $serverDefinition);

        if (!empty($handlerInfo['logger'])) {
            $logger = new Reference($handlerInfo['logger']);
            $handlerDefinition->replaceArgument(1, $logger);
        }

        if (!empty($handlerInfo['serializer'])) {
            $serializer = new Reference($handlerInfo['serializer']);
            $handlerDefinition->replaceArgument(2, $serializer);
        }

        $handlerDefinition->replaceArgument(4, !empty($handlerInfo['annotation']));

        $handlerDefinition->addTag(self::HANDLER_TAG, ['key' => $handlerKey,]);

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
