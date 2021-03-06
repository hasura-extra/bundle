<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Bundle\Tests\Fixture\App\GraphQL\Mutation;

use Symfony\Component\Validator\Constraints as Assertion;
use TheCodingMachine\GraphQLite\Annotations\Field;
use TheCodingMachine\GraphQLite\Annotations\Input;

#[Input(name: 'sub_input_object_assertion', default: true)]
final class SubInputObjectAssertion
{
    #[Field(name: 'sub_text_field')]
    #[Assertion\NotBlank]
    public string $subTextField;
}