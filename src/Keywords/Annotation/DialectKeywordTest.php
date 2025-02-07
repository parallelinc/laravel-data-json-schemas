<?php

use BasilLangevin\LaravelDataJsonSchemas\Enums\DataType;
use BasilLangevin\LaravelDataJsonSchemas\Enums\JsonSchemaDialect;
use BasilLangevin\LaravelDataJsonSchemas\Keywords\Annotation\DialectKeyword;
use BasilLangevin\LaravelDataJsonSchemas\Keywords\General\TypeKeyword;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\StringSchema;

covers(DialectKeyword::class);

class DialectKeywordTestSchema extends StringSchema
{
    public static array $keywords = [
        TypeKeyword::class,
        DialectKeyword::class,
    ];
}

$basicOutput = collect([
    'type' => DataType::String->value,
]);

it('can set its dialect')
    ->expect(DialectKeywordTestSchema::make()->dialect(JsonSchemaDialect::Draft201909))
    ->getDialect()->toBe(JsonSchemaDialect::Draft201909);

it('can apply the dialect to a schema')
    ->expect(DialectKeywordTestSchema::make())
    ->dialect(JsonSchemaDialect::Draft201909)
    ->applyKeyword(DialectKeyword::class, $basicOutput)
    ->toEqual(collect([
        'type' => DataType::String->value,
        '$schema' => JsonSchemaDialect::Draft201909->value,
    ]));
