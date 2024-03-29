<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Bundle\DependencyInjection;

use Symfony\Bundle\MakerBundle\MakerBundle;
use Symfony\Bundle\SecurityBundle\SecurityBundle;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{

    public function getConfigTreeBuilder(): TreeBuilder
    {
        $builder = new TreeBuilder('hasura');
        $root = $builder->getRootNode();
        $root
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('base_uri')
                    ->cannotBeEmpty()
                    ->defaultValue('http://hasura:8080')
                    ->info('Hasura base uri')
                ->end()
                ->scalarNode('admin_secret')
                    ->cannotBeEmpty()
                    ->defaultNull()
                    ->info('Hasura admin secret')
                ->end()
                ->scalarNode('remote_schema_name')
                    ->cannotBeEmpty()
                    ->defaultNull()
                    ->info('Application remote schema name had added on Hasura.')
                ->end()
                ->arrayNode('metadata')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('path')
                            ->cannotBeEmpty()
                            ->defaultValue('%kernel.project_dir%/hasura/metadata')
                            ->info('Path store your Hasura metadata.')
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('auth')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('anonymous_role')
                            ->cannotBeEmpty()
                            ->defaultValue('ROLE_ANONYMOUS')
                            ->info('Role for unauthenticated user.')
                        ->end()
                        ->scalarNode('default_role')
                            ->cannotBeEmpty()
                            ->defaultValue('ROLE_USER')
                            ->info('Default role for authenticated user when user not request role via `x-hasura-role` header.')
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
        return $builder;
    }
}