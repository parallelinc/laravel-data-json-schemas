<?php

use BasilLangevin\LaravelDataSchemas\Keywords\Annotation\DescriptionKeyword;
use BasilLangevin\LaravelDataSchemas\Schemas\Concerns\ConstructsSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\Concerns\PrimitiveSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\Contracts\Schema;

covers(ConstructsSchema::class);

class ConstructsSchemaTestSchema implements Schema
{
    use PrimitiveSchema;

    public static array $keywords = [
        DescriptionKeyword::class,
    ];
}

it('can be created with the make method')
    ->expect(ConstructsSchemaTestSchema::make('test', 'test description'))
    ->toBeInstanceOf(ConstructsSchemaTestSchema::class)
    ->getName()->toBe('test')
    ->getDescription()->toBe('test description');

it('has a name')
    ->expect(new ConstructsSchemaTestSchema('test'))
    ->getName()->toBe('test');

it('can leave the name empty')
    ->expect(ConstructsSchemaTestSchema::make())
    ->getName()->toBe('');

it('can set its name after construction')
    ->expect(ConstructsSchemaTestSchema::make())
    ->name('test')
    ->getName()->toBe('test');

it('can set its description on construction')
    ->expect(fn () => ConstructsSchemaTestSchema::make('test', 'test description'))
    ->getDescription()->toBe('test description');

it('can set its description after construction')
    ->expect(ConstructsSchemaTestSchema::make('test'))
    ->description('test description')
    ->getDescription()->toBe('test description');

it('can convert to an array')
    ->expect(ConstructsSchemaTestSchema::make('test'))
    ->description('test description')
    ->toArray()
    ->toBe([
        'description' => 'test description',
    ]);
