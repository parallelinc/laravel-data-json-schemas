<?php

namespace BasilLangevin\LaravelDataJsonSchemas\Actions;

use BasilLangevin\LaravelDataJsonSchemas\Actions\Concerns\Runnable;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\Contracts\Schema;
use BasilLangevin\LaravelDataJsonSchemas\Support\PropertyWrapper;

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
