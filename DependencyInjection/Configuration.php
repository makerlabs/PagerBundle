<?php

namespace MakerLabs\PagerBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('maker_labs_pager');

        $rootNode
            ->children()
                ->scalarNode('limit')->defaultValue('20')->end()
                ->scalarNode('max_pages')->defaultValue('10')->end()
                ->scalarNode('template')->defaultNull()->end()
            ->end()
            ;
        return $treeBuilder;
    }
}
