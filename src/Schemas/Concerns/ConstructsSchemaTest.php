<?php

use BasilLangevin\LaravelDataJsonSchemas\Keywords\Annotation\DescriptionKeyword;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\Concerns\ConstructsSchema;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\Concerns\SingleTypeSchemaTrait;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\Contracts\SingleTypeSchema;

covers(ConstructsSchema::class);

class ConstructsSchemaTestSchema implements SingleTypeSchema
{
    use SingleTypeSchemaTrait;

    public static array $keywords = [
        DescriptionKeyword::class,
    ];
}

it('can be created with the make method')
    ->expect(ConstructsSchemaTestSchema::make())
    ->toBeInstanceOf(ConstructsSchemaTestSchema::class);
