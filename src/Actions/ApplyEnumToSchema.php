<?php

namespace BasilLangevin\LaravelDataSchemas\Actions;

use BasilLangevin\LaravelDataSchemas\Actions\Concerns\Runnable;
use BasilLangevin\LaravelDataSchemas\Schemas\Contracts\Schema;
use BasilLangevin\LaravelDataSchemas\Support\PropertyWrapper;

class ApplyEnumToSchema
{
    /** @use Runnable<Schema> */
    use Runnable;

    public function handle(Schema $schema, PropertyWrapper $property): Schema
    {
        return $schema->enum($property->getTypeName());
    }
}
