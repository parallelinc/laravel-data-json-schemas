<?php

use BasilLangevin\LaravelDataJsonSchemas\Enums\DataType;
use BasilLangevin\LaravelDataJsonSchemas\Enums\Format;
use BasilLangevin\LaravelDataJsonSchemas\Exceptions\SchemaConfigurationException;
use BasilLangevin\LaravelDataJsonSchemas\Keywords\General\FormatKeyword;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\StringSchema;

covers(FormatKeyword::class);

$basicOutput = collect([
    'type' => DataType::String->value,
]);

it('can set its format')
    ->expect(StringSchema::make()->format(Format::DateTime))
    ->getFormat()->toBe(Format::DateTime);

it('can get its format')
    ->expect(StringSchema::make()->format(Format::DateTime))
    ->getFormat()->toBe(Format::DateTime);

it('can apply the format to a schema')
    ->expect(StringSchema::make()->format(Format::DateTime))
    ->applyKeyword(FormatKeyword::class, $basicOutput)
    ->toEqual(collect([
        'type' => DataType::String->value,
        'format' => Format::DateTime->value,
    ]));

it('can apply a Format enum value to a schema')
    ->expect(StringSchema::make()->format(Format::DateTime->value))
    ->applyKeyword(FormatKeyword::class, $basicOutput)
    ->toEqual(collect([
        'type' => DataType::String->value,
        'format' => Format::DateTime->value,
    ]));

it('applies the format when multiple instances with the same format are applied')
    ->expect(StringSchema::make()->format(Format::DateTime)->format(Format::DateTime))
    ->applyKeyword(FormatKeyword::class, $basicOutput)
    ->toEqual(collect([
        'type' => DataType::String->value,
        'format' => Format::DateTime->value,
    ]));

it('throws an exception if multiple instances with different formats are applied', function () {
    StringSchema::make()->format(Format::DateTime)->format(Format::Email)->toArray();
})->throws(SchemaConfigurationException::class, 'A schema cannot have more than one format.');
