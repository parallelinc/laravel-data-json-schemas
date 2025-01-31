<?php

use BasilLangevin\LaravelDataSchemas\Keywords\Annotation\DescriptionKeyword;
use BasilLangevin\LaravelDataSchemas\Schemas\Concerns\ConstructsSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\Concerns\SingleTypeSchemaTrait;
use BasilLangevin\LaravelDataSchemas\Schemas\Contracts\SingleTypeSchema;

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
