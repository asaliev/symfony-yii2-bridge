<?php

namespace Asaliev\Yii2Bridge\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('yii2');
        $rootNode = $treeBuilder->getRootNode();

        // Define the structure of your configuration
        $rootNode
            ->children()
            ->scalarNode('web_config_path')
            ->defaultValue('%kernel.project_dir%/config/web_config.php')
            ->info('Path to Yii2 web config file')
            ->end()

            ->scalarNode('override_yii_container_class')
            ->defaultNull()
            ->info('Allows overriding which class is used for the Yii2 container')
            ->end()

            ->scalarNode('messenger_bus')
            ->defaultValue('messenger.default_bus')
            ->info('The messenger bus to use for dispatching messages')
            ->end()

            ->end();

        return $treeBuilder;
    }
}
