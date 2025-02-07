<?php

use BasilLangevin\LaravelDataJsonSchemas\Enums\DataType;
use BasilLangevin\LaravelDataJsonSchemas\Keywords\Annotation\DescriptionKeyword;
use BasilLangevin\LaravelDataJsonSchemas\Keywords\General\TypeKeyword;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\StringSchema;

covers(DescriptionKeyword::class);

class DescriptionKeywordTestSchema extends StringSchema
{
    public static array $keywords = [
        TypeKeyword::class,
        DescriptionKeyword::class,
    ];
}

$basicOutput = collect([
    'type' => DataType::String->value,
]);

it('can set its description')
    ->expect(DescriptionKeywordTestSchema::make())
    ->description('test description')
    ->getDescription()->toBe('test description');

it('can apply the description to a schema')
    ->expect(DescriptionKeywordTestSchema::make())
    ->description('test description')
    ->applyKeyword(DescriptionKeyword::class, $basicOutput)
    ->toEqual(collect([
        'type' => DataType::String->value,
        'description' => 'test description',
    ]));
