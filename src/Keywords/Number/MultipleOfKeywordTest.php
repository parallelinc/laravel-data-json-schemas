<?php

use BasilLangevin\LaravelDataJsonSchemas\Enums\DataType;
use BasilLangevin\LaravelDataJsonSchemas\Keywords\Number\MultipleOfKeyword;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\NumberSchema;

covers(MultipleOfKeyword::class);

$basicOutput = collect([
    'type' => DataType::Number->value,
]);

it('can set its multiple of value')
    ->expect(fn () => NumberSchema::make()->multipleOf(42))
    ->getMultipleOf()->toBe(42);

it('can get its exclusive minimum value')
    ->expect(NumberSchema::make()->multipleOf(42))
    ->getMultipleOf()->toBe(42);

it('can apply the multiple of value to a schema')
    ->expect(NumberSchema::make()->multipleOf(42))
    ->applyKeyword(MultipleOfKeyword::class, $basicOutput)
    ->toEqual(collect([
        'type' => DataType::Number->value,
        'multipleOf' => 42,
    ]));
