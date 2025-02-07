<?php

use BasilLangevin\LaravelDataJsonSchemas\Enums\DataType;
use BasilLangevin\LaravelDataJsonSchemas\Keywords\Array\MaxItemsKeyword;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\ArraySchema;

covers(MaxItemsKeyword::class);

$basicOutput = collect([
    'type' => DataType::Array->value,
]);

it('can set its maximum value')
    ->expect(fn () => ArraySchema::make()->maxItems(42))
    ->getMaxItems()->toBe(42);

it('can get its maximum value')
    ->expect(ArraySchema::make()->maxItems(42))
    ->getMaxItems()->toBe(42);

it('can apply the maximum value to a schema')
    ->expect(ArraySchema::make()->maxItems(42))
    ->applyKeyword(MaxItemsKeyword::class, $basicOutput)
    ->toEqual(collect([
        'type' => DataType::Array->value,
        'maxItems' => 42,
    ]));

it('chooses the lowest value when multiple instances are set')
    ->expect(ArraySchema::make()->maxItems(42)->maxItems(43))
    ->applyKeyword(MaxItemsKeyword::class, $basicOutput)
    ->toEqual(collect([
        'type' => DataType::Array->value,
        'maxItems' => 42,
    ]));
