<?php

use BasilLangevin\LaravelDataJsonSchemas\Enums\DataType;
use BasilLangevin\LaravelDataJsonSchemas\Keywords\Object\AdditionalPropertiesKeyword;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\ObjectSchema;

covers(AdditionalPropertiesKeyword::class);

$basicOutput = collect([
    'type' => DataType::Object->value,
]);

it('can set its additional properties value')
    ->expect(fn () => ObjectSchema::make()->additionalProperties(true))
    ->getAdditionalProperties()->toBeTrue();

it('can get its additional properties value')
    ->expect(ObjectSchema::make()->additionalProperties(true))
    ->getAdditionalProperties()->toBeTrue();

it('can apply the additional properties value to a schema')
    ->expect(ObjectSchema::make()->additionalProperties(false))
    ->applyKeyword(AdditionalPropertiesKeyword::class, $basicOutput)
    ->toEqual(collect([
        'type' => DataType::Object->value,
        'additionalProperties' => false,
    ]));
