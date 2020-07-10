<?php

declare(strict_types=1);

namespace Prony\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('prony');
        $rootNode = $treeBuilder->getRootNode();

        $this->addResourceSection($rootNode);
        $this->addElasticSearchSection($rootNode);

        return $treeBuilder;
    }

    private function addResourceSection(ArrayNodeDefinition $node): void
    {
        $node
            ->children()
                ->arrayNode('resources')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('workspace')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->arrayNode('classes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('model')->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('board')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->arrayNode('classes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('model')->end()
                                        ->scalarNode('manager')->end()
                                        ->scalarNode('repository')->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('tag')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->arrayNode('classes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('model')->end()
                                        ->scalarNode('manager')->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('status')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->arrayNode('classes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('model')->end()
                                        ->scalarNode('manager')->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('post')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->arrayNode('classes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('model')->end()
                                        ->scalarNode('repository')->end()
                                        ->scalarNode('manager')->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('comment')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->arrayNode('classes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('model')->end()
                                        ->scalarNode('repository')->end()
                                        ->scalarNode('manager')->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }

    private function addElasticSearchSection(ArrayNodeDefinition $node): void
    {
        $node
            ->children()
                ->arrayNode('elasticsearch')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('enabled')->defaultTrue()->end()
                    ->end()
                ->end()
            ->end();
    }
}
