<?php

namespace BasilLangevin\LaravelDataSchemas\Actions;

use BasilLangevin\LaravelDataSchemas\Actions\Concerns\Runnable;
use BasilLangevin\LaravelDataSchemas\Schemas\Contracts\Schema;
use BasilLangevin\LaravelDataSchemas\Support\PropertyWrapper;

class ApplyEnumToSchema
{
    /** @use Runnable<array{Schema, PropertyWrapper}, Schema> */
    use Runnable;

    /**
     * Set the Schema's "enum" keyword to the enum's values.
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
