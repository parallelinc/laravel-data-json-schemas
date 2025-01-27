<?php

namespace BasilLangevin\LaravelDataSchemas\Actions;

use BasilLangevin\LaravelDataSchemas\Actions\Concerns\Runnable;
use BasilLangevin\LaravelDataSchemas\Schemas\Contracts\Schema;
use BasilLangevin\LaravelDataSchemas\Support\PropertyWrapper;

class TransformPropertyToSchema
{
    use Runnable;

    public function handle(PropertyWrapper $property): Schema
    {
        return MakeSchemaForReflectionType::run($property->getType(), $property->getName())
            ->pipe(fn (Schema $schema) => ApplyTypeToSchema::run($schema, $property))
            ->when($property->isEnum(), fn (Schema $schema) => ApplyEnumToSchema::run($schema, $property))
            ->pipe(fn (Schema $schema) => ApplyAnnotationsToSchema::run($schema, $property))
            ->when($property->isDateTime(), fn (Schema $schema) => ApplyDateTimeFormatToSchema::run($schema))
            ->pipe(fn (Schema $schema) => ApplyRuleConfigurationsToSchema::run($schema, $property));
    }
}
