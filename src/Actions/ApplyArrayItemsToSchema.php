<?php

namespace BasilLangevin\LaravelDataJsonSchemas\Actions;

use BasilLangevin\LaravelDataJsonSchemas\Actions\Concerns\Runnable;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\ArraySchema;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\BooleanSchema;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\Contracts\Schema;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\Contracts\SingleTypeSchema;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\IntegerSchema;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\NullSchema;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\NumberSchema;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\ObjectSchema;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\StringSchema;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\UnionSchema;
use BasilLangevin\LaravelDataJsonSchemas\Support\PropertyWrapper;
use BasilLangevin\LaravelDataJsonSchemas\Support\SchemaTree;
use Spatie\LaravelData\Support\Types\NamedType;

class ApplyArrayItemsToSchema
{
    /** @use Runnable<array{ArraySchema|UnionSchema, PropertyWrapper, SchemaTree}, ArraySchema|UnionSchema> */
    use Runnable;

    /**
     * Set the Schema's "items" keyword to the Schema of the items.
     */
    public function handle(ArraySchema|UnionSchema $schema, PropertyWrapper $property, SchemaTree $tree): ArraySchema|UnionSchema
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
        $class = $property->getDataClassName();

        if (! $class) {
            return null;
        }

        return TransformDataClassToSchema::run($class, $tree);
    }

    protected function getIterableSchema(PropertyWrapper $property): ?Schema
    {
        /** @var NamedType $type */
        $type = $property->getType();

        $iterableType = $type->iterableItemType;

        /** @var class-string<SingleTypeSchema>|null $schemaClass */
        $schemaClass = match (true) {
            $iterableType === 'array' => ArraySchema::class,
            $iterableType === 'bool' => BooleanSchema::class,
            $iterableType === 'float' => NumberSchema::class,
            $iterableType === 'int' => IntegerSchema::class,
            $iterableType === 'null' => NullSchema::class,
            $iterableType === 'object' => ObjectSchema::class,
            $iterableType === 'string' => StringSchema::class,
            default => null,
        };

        if (is_null($schemaClass)) {
            return null;
        }

        return $schemaClass::make()->applyType();
    }
}
