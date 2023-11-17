<?php

declare(strict_types=1);

/*
 * This file is part of Wemea Menu plugin for Sylius.
 *
 * (c) Wemea (wemea.fr)
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

namespace Wemea\SyliusMenuPlugin\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    /**
     * @psalm-suppress UnusedVariable
     * @psalm-suppress UndefinedMethod
     * @psalm-suppress MixedMethodCall
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('wemea_sylius_menu_plugin');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->arrayNode('resource_path_resolver_configuration')
//                    FIXME : is possible to had here the default conf ? and Not in extension class
//                    ->ignoreExtraKeys()
//                    ->children()
//                        ->append($this->defaultResourcePathConfiguration('custom', null, []))
//                        ->append($this->defaultResourcePathConfiguration('product', 'sylius_shop_product_show', [ 'slug' => 'getSlug']))
//                        ->append($this->defaultResourcePathConfiguration('taxon', 'sylius_shop_product_index', [ 'slug' => 'getSlug']))
//                    ->end()

                    ->useAttributeAsKey('resource_property')
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('route')->info('Route name to resolve resource path')
                            ->end()

                            ->arrayNode('parameters')
                                ->defaultValue([])
                                ->useAttributeAsKey('route_parameter')
                                ->scalarPrototype()->info('Association of route parameters and resource method to access it')
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ->end()
        ;

        return $treeBuilder;
    }
}
