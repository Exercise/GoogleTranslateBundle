<?php

namespace Exercise\GoogleTranslateBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('exercise_google_translate');

        $yamlIndent = array(2, 4);

        $rootNode
            ->children()

                ->scalarNode('api_key')
                    ->isRequired()
                    ->cannotBeEmpty()
                    ->info('key for your app https://code.google.com/apis/console/b/0/?pli=1#project:247987860421:access')
                ->end()

                ->scalarNode('yaml_indent')
                    ->cannotBeEmpty()
                    ->defaultValue(4)
                    ->validate()
                        ->ifNotInArray($yamlIndent)
                        ->thenInvalid('The input type "%s" is not supported. Please use one of the following values: '.implode(', ', $yamlIndent))
                ->end()

            ->end()
        ;

        return $treeBuilder;
    }
}
