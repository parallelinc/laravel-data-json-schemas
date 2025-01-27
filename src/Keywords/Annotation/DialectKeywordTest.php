<?php

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Enums\JsonSchemaDialect;
use BasilLangevin\LaravelDataSchemas\Keywords\Annotation\DialectKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\General\TypeKeyword;
use BasilLangevin\LaravelDataSchemas\Schemas\StringSchema;

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
