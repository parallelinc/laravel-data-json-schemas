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
        return MakeSchemaForReflectionType::run($property->getReflectionType(), $property->getName())
            ->pipe(fn (Schema $schema) => ApplyTypeToSchema::run($schema, $property))
            ->pipe(fn (Schema $schema) => ApplyAnnotationsToSchema::run($schema, $property))
            ->when($property->isEnum(), fn (Schema $schema) => ApplyEnumToSchema::run($schema, $property))
            ->when($property->isDateTime(), fn (Schema $schema) => ApplyDateTimeFormatToSchema::run($schema))
            ->when($property->isArray(), fn (Schema $schema) => ApplyArrayItemsToSchema::run($schema, $property))
            ->pipe(fn (Schema $schema) => ApplyRuleConfigurationsToSchema::run($schema, $property))
            ->when($property->isDataObject(), function (Schema $schema) use ($property) {
                $class = $property->getDataClass();

                ApplyPropertiesToDataObjectSchema::run($schema, $class);
                ApplyRequiredToDataObjectSchema::run($schema, $class);

                return $schema;
            });
    }
}
