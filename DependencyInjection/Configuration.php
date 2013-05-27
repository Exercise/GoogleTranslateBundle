<?php

namespace Exercise\GTranslateBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('exercise_g_translate');

        $rootNode
            ->children()
                ->scalarNode('api_key')
                    ->info('key for your app https://code.google.com/apis/console/b/0/?pli=1#project:247987860421:access')
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
