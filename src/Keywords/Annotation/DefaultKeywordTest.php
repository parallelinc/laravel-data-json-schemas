<?php

use BasilLangevin\LaravelDataJsonSchemas\Enums\DataType;
use BasilLangevin\LaravelDataJsonSchemas\Keywords\Annotation\DefaultKeyword;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\BooleanSchema;

covers(DefaultKeyword::class);

$basicOutput = collect([
    'type' => DataType::Boolean->value,
]);

it('can set its default value')
    ->expect(BooleanSchema::make()->default(true))
    ->getDefault()->toBe(true);

it('can apply the default value to a schema')
    ->expect(BooleanSchema::make()->default(true))
    ->applyKeyword(DefaultKeyword::class, $basicOutput)
    ->toEqual(collect([
        'type' => DataType::Boolean->value,
        'default' => true,
    ]));
