<?php

namespace BasilLangevin\LaravelDataSchemas\Actions;

use BasilLangevin\LaravelDataSchemas\Actions\Concerns\Runnable;
use BasilLangevin\LaravelDataSchemas\Schemas\ArraySchema;
use BasilLangevin\LaravelDataSchemas\Schemas\BooleanSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\Contracts\Schema;
use BasilLangevin\LaravelDataSchemas\Schemas\IntegerSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\NullSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\NumberSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\ObjectSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\StringSchema;
use BasilLangevin\LaravelDataSchemas\Support\PropertyWrapper;
use BasilLangevin\LaravelDataSchemas\Support\SchemaTree;

class applyarrayitemstoschema
{
    use Runnable;

    public function handle(Schema $schema, PropertyWrapper $property, SchemaTree $tree): Schema
    {
        $itemsSchema = $this->getDataClassSchema($property, $tree)
            ?? $this->getIterableSchema($property);

        if (! $itemsSchema) {
            return $schema;
        }

        return $schema->items($itemsSchema);
    }

    protected function getDataClassSchema(PropertyWrapper $property, SchemaTree $tree): ?Schema
    {
        $class = $property->getType()->dataClass;

        if (! $class) {
            return null;
        }

        return TransformDataClassToSchema::run($class, $tree);
    }

    protected function getIterableSchema(PropertyWrapper $property): ?Schema
    {
        $type = $property->getType()->iterableItemType;

        $schemaClass = match (true) {
            $type === 'array' => ArraySchema::class,
            $type === 'bool' => BooleanSchema::class,
            $type === 'float' => NumberSchema::class,
            $type === 'int' => IntegerSchema::class,
            $type === 'null' => NullSchema::class,
            $type === 'object' => ObjectSchema::class,
            $type === 'string' => StringSchema::class,
            default => null,
        };

        if (! $schemaClass) {
            return null;
        }

        return $schemaClass::make()->applyType();
    }
}
