<?php

namespace BasilLangevin\LaravelDataSchemas\Actions;

use BasilLangevin\LaravelDataSchemas\Actions\Concerns\Runnable;
use BasilLangevin\LaravelDataSchemas\Schemas\ArraySchema;
use BasilLangevin\LaravelDataSchemas\Schemas\BooleanSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\Contracts\Schema;
use BasilLangevin\LaravelDataSchemas\Schemas\Contracts\SingleTypeSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\IntegerSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\NullSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\NumberSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\ObjectSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\StringSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\UnionSchema;
use BasilLangevin\LaravelDataSchemas\Support\PropertyWrapper;
use BasilLangevin\LaravelDataSchemas\Support\SchemaTree;
use Spatie\LaravelData\Support\Types\NamedType;

class ApplyArrayItemsToSchema
{
    /** @use Runnable<ArraySchema|UnionSchema> */
    use Runnable;

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
