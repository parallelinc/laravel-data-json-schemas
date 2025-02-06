<?php

namespace BasilLangevin\LaravelDataSchemas\Actions;

use BasilLangevin\LaravelDataSchemas\Actions\Concerns\Runnable;
use BasilLangevin\LaravelDataSchemas\Schemas\Contracts\Schema;
use BasilLangevin\LaravelDataSchemas\Support\PropertyWrapper;

class ApplyEnumToSchema
{
    /** @use Runnable<Schema> */
    use Runnable;

    /**
     * Apply the enum to the schema.
     */
    public function handle(Schema $schema, PropertyWrapper $property): Schema
    {
        $enum = $property->getTypeName();

        if (is_null($enum) || ! enum_exists($enum)) {
            return $schema;
        }

        return $schema->enum($enum);
    }
}
