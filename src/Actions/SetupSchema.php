<?php

namespace BasilLangevin\LaravelDataJsonSchemas\Actions;

use BasilLangevin\LaravelDataJsonSchemas\Actions\Concerns\Runnable;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\Contracts\Schema;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\UnionSchema;
use BasilLangevin\LaravelDataJsonSchemas\Support\PropertyWrapper;
use BasilLangevin\LaravelDataJsonSchemas\Support\SchemaTree;

class SetupSchema
{
    /** @use Runnable<array{Schema, PropertyWrapper, SchemaTree}, Schema> */
    use Runnable;

    /**
     * Instantiate any constituent Schemas and add the "type" keyword.
     */
    public function handle(Schema $schema, PropertyWrapper $property, SchemaTree $tree): Schema
    {
        if ($schema instanceof UnionSchema) {
            return $schema->buildConstituentSchemas($property, $tree);
        }

        return $schema->applyType();
    }
}
