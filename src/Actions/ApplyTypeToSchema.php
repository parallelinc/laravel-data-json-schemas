<?php

namespace BasilLangevin\LaravelDataSchemas\Actions;

use BasilLangevin\LaravelDataSchemas\Actions\Concerns\Runnable;
use BasilLangevin\LaravelDataSchemas\Schemas\Contracts\Schema;
use BasilLangevin\LaravelDataSchemas\Schemas\UnionSchema;
use BasilLangevin\LaravelDataSchemas\Support\PropertyWrapper;
use BasilLangevin\LaravelDataSchemas\Support\SchemaTree;

class ApplyTypeToSchema
{
    use Runnable;

    public function handle(Schema $schema, PropertyWrapper $property, SchemaTree $tree): Schema
    {
        // This check exists to satisfy PHPStan.
        if ($schema instanceof UnionSchema) { // @pest-mutate-ignore
            return $schema->applyType($property, $tree);
        }

        return $schema->applyType();
    }
}
