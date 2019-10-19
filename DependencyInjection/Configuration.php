<?php

namespace Insidestyles\JsonRpcBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\NodeBuilder;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @author Fuong <insidestyles@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('json_rpc_api');

        $node = $rootNode->children();

        $this->configHandlersSection($node);

        return $treeBuilder;
    }

    /**
     * Build handlers section
     */
    public function configHandlersSection(NodeBuilder $node)
    {
        $node
            ->arrayNode('handlers')
                ->arrayPrototype()
                ->children()
                    ->scalarNode('path')
                        ->isRequired()
                        ->info('The path for running this handler.')
                        ->example('/api')
                    ->end()
                    ->scalarNode('host')
                        ->defaultValue(null)
                        ->info('The host for running this handler.')
                        ->example('rpc.domain.com')
                    ->end()
                    ->scalarNode('annotation')
                        ->defaultValue(true)
                        ->info('Enable annotation for running this handler.')
                        ->end()
                    ->scalarNode('logger')
                        ->defaultValue(null)
                        ->info('Enable logger for running this handler.')
                    ->end()
                    ->scalarNode('serializer')
                        ->defaultValue(null)
                        ->info('Enable serializer for running this handler.')
                    ->end()
                    ->scalarNode('context')
                        ->defaultValue(null)
                        ->info('Enable serializer context for running this handler.')
                    ->end()
                    ->scalarNode('cache')
                        ->defaultValue(null)
                        ->info('Enable cache for running this handler.')
                    ->end()
                ->end()
            ->end();
    }
}
