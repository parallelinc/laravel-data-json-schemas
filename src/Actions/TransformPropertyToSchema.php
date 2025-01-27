<?php

namespace BasilLangevin\LaravelDataSchemas\Actions;

use BasilLangevin\LaravelDataSchemas\Enums\Format;
use BasilLangevin\LaravelDataSchemas\Support\PropertyWrapper;
use BasilLangevin\LaravelDataSchemas\Schemas\Contracts\Schema;
use BasilLangevin\LaravelDataSchemas\Actions\Concerns\Runnable;

class TransformPropertyToSchema
{
    use Runnable;

    public function handle(PropertyWrapper $property): Schema
    {
        return MakeSchemaForReflectionType::run($property->getType(), $property->getName())
            ->pipe(fn (Schema $schema) => ApplyTypeToSchema::run($schema, $property))
            ->when($property->isEnum(), fn (Schema $schema) => ApplyEnumToSchema::run($schema, $property))
            ->pipe(fn (Schema $schema) => ApplyAnnotationsToSchema::run($schema, $property))
            ->when($property->isDateTime(), fn (Schema $schema) => $schema->format(Format::DateTime))
            ->pipe(fn (Schema $schema) => ApplyRuleConfigurationsToSchema::run($schema, $property));
    }
}
