<?php

namespace BasilLangevin\LaravelDataSchemas\Actions;

use BasilLangevin\LaravelDataSchemas\Actions\Concerns\Runnable;
use BasilLangevin\LaravelDataSchemas\Schemas\ArraySchema;
use BasilLangevin\LaravelDataSchemas\Schemas\Contracts\Schema;
use BasilLangevin\LaravelDataSchemas\Schemas\StringSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\UnionSchema;
use BasilLangevin\LaravelDataSchemas\Support\PropertyWrapper;
use BasilLangevin\LaravelDataSchemas\Support\SchemaTree;

class TransformPropertyToSchema
{
    /** @use Runnable<array{PropertyWrapper, SchemaTree}, Schema> */
    use Runnable;

    /**
     * Transform a property to a Schema with the appropriate keywords.
     */
    public function handle(PropertyWrapper $property, SchemaTree $tree): Schema
    {
        if ($property->isDataObject()) {
            return TransformDataClassToSchema::run($property->getDataClassName(), $tree);
        }

        return MakeSchemaForReflectionType::run($property->getReflectionType())
            ->pipe(fn (Schema $schema) => SetupSchema::run($schema, $property, $tree))
            ->pipe(fn (Schema $schema) => ApplyAnnotationsToSchema::run($schema, $property))
            ->when($property->isEnum(), fn (Schema $schema) => ApplyEnumToSchema::run($schema, $property))
            ->when($property->isDateTime(), fn (StringSchema|UnionSchema $schema) => ApplyDateTimeFormatToSchema::run($schema))
            ->when($property->isArray(), fn (ArraySchema|UnionSchema $schema) => ApplyArrayItemsToSchema::run($schema, $property, $tree))
            ->pipe(fn (Schema $schema) => ApplyRuleConfigurationsToSchema::run($schema, $property))
            ->tree($tree);
    }
}
