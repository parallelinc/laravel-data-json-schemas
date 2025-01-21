<?php

namespace BasilLangevin\LaravelDataSchemas\Schemas\Concerns;

use BasilLangevin\LaravelDataSchemas\Keywords\Annotation\DescriptionKeyword;
use BasilLangevin\LaravelDataSchemas\Schemas\Contracts\Schema;

covers(PrimitiveSchema::class);

class PrimitiveSchemaTestSchema implements Schema
{
    use PrimitiveSchema;

    public static array $keywords = [
        DescriptionKeyword::class,
    ];
}

it('can convert to an array')
    ->expect(PrimitiveSchemaTestSchema::make('test'))
    ->description('test description')
    ->toArray()
    ->toBe([
        'description' => 'test description',
    ]);

it('can clone its base structure', function () {
    $schema = PrimitiveSchemaTestSchema::make('test');
    $schema->description('test description');

    $clone = $schema->cloneBaseStructure();

    expect($clone)->toBeInstanceOf(PrimitiveSchemaTestSchema::class);
    expect($clone->getName())->toBe('');
    expect(fn () => $clone->getDescription())->toThrow(\Exception::class, 'The keyword "description" has not been set.');
});
