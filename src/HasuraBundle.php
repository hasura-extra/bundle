<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Bundle;

use Hasura\Bundle\DependencyInjection\CompilerPass\DecorateMakeEntityPass;
use Hasura\Bundle\DependencyInjection\CompilerPass\GraphQLiteMiddlewarePass;
use Hasura\Bundle\DependencyInjection\CompilerPass\GraphQLitePass;
use Hasura\Bundle\DependencyInjection\CompilerPass\MetadataPass;
use Spawnia\Sailor\Configuration;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class HasuraBundle extends Bundle
{
    public function boot()
    {
        $sailorConfig = $this->container->get('hasura.sailor.endpoint_config');

        Configuration::setEndpoint('hasura', $sailorConfig);
    }

    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new DecorateMakeEntityPass());
        $container->addCompilerPass(new MetadataPass());

        $this->addGraphQLitePasses($container);
    }

    private function addGraphQLitePasses(ContainerBuilder $container): void
    {
        $container->addCompilerPass(
            new GraphQLiteMiddlewarePass(
                [
                    // object assertion must be run before field arg naming for support naming violation property path.
                    'hasura.graphql.parameter.object_assertion_middleware',
                    'hasura.graphql.parameter.arg_naming_middleware',
                    'hasura.graphql.parameter.avoid_explicit_default_null_middleware'
                ],
                [
                    'hasura.graphql.field.object_assertion_middleware',
                    'hasura.graphql.field.annotation_tracking_middleware',
                    'hasura.graphql.field.arg_naming_middleware',
                ]
            ),
            priority: -2
        );

        if ($container->hasExtension('doctrine')) {
            $container->addCompilerPass(
                new GraphQLiteMiddlewarePass(
                    [
                        'hasura.graphql.parameter.arg_entity_middleware'
                    ],
                    [
                        'hasura.graphql.field.transactional_middleware',
                    ]
                ),
                priority: -4
            );
        }

        $container->addCompilerPass(new GraphQLitePass(), priority: -6);
    }
}