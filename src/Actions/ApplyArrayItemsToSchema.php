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
        // 1) Try to resolve union item types from docblocks (property, class, constructor param)
        $unionItemTypes = $this->getIterableItemUnionTypes($property);

        if (! empty($unionItemTypes)) {
            $resolvedSchemas = [];

            foreach ($unionItemTypes as $itemType) {
                $itemSchema = $this->makeSchemaForItemType($itemType, $tree);
                if ($itemSchema instanceof Schema) {
                    $resolvedSchemas[] = $itemSchema;
                }
            }

            if (count($resolvedSchemas) >= 2) {
                foreach ($resolvedSchemas as $itemSchema) {
                    $schema = $schema->items($itemSchema);
                }

                return $schema;
            }

            if (count($resolvedSchemas) === 1) {
                return $schema->items($resolvedSchemas[0]);
            }
            // If we couldn't resolve any schemas from the docblock, fall through to existing logic
        }

        // 2) Fallbacks: Data class items or single primitive/object item type
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

    /**
     * Extract union item types from docblocks if present.
     *
     * @return array<int, string> A list of item type tokens (e.g., 'string', 'int')
     */
    protected function getIterableItemUnionTypes(PropertyWrapper $property): array
    {
        // Check property-level @var
        $propDoc = $property->getDocBlock();
        $typeString = $propDoc?->getVarType();

        // Check class-level @var $property
        if (empty($typeString)) {
            $classDoc = $property->getClass()->getDocBlock();
            $typeString = $classDoc?->getVarType($property->getName());
        }

        // Check constructor-level @param $property
        if (empty($typeString)) {
            $ctorDoc = $property->getClass()->getConstructorDocBlock();
            $typeString = $ctorDoc?->getParamType($property->getName());
        }

        if (empty($typeString)) {
            return [];
        }

        return $this->parseUnionItemTypesFromArrayType($typeString);
    }

    /**
     * Parse a phpdoc array type like "array<int, string|int>" and return union item types.
     *
     * @return array<int, string>
     */
    protected function parseUnionItemTypesFromArrayType(string $docType): array
    {
        $docType = trim($docType);

        // Match array<key, value>
        if (preg_match('/^array\s*<\s*[^,>]+\s*,\s*(.+?)\s*>$/i', $docType, $matches) === 1) {
            $valueExpr = $matches[1];
        } elseif (preg_match('/^array\s*<\s*(.+?)\s*>$/i', $docType, $matches) === 1) {
            // Match array<value>
            $valueExpr = $matches[1];
        } else {
            // Not an array generic or unsupported
            return [];
        }

        // Remove wrapping parentheses
        $valueExpr = trim($valueExpr);
        if (str_starts_with($valueExpr, '(') && str_ends_with($valueExpr, ')')) {
            $valueExpr = substr($valueExpr, 1, -1);
        }

        // Split union by |
        $parts = array_map('trim', explode('|', $valueExpr));

        // Filter empty
        $parts = array_values(array_filter($parts, fn ($p) => $p !== ''));

        return $parts;
    }

    /**
     * Map a type token to a Schema instance for items.
     */
    protected function makeSchemaForItemType(string $typeToken, SchemaTree $tree): ?Schema
    {
        $normalized = strtolower($typeToken);

        /** @var class-string<SingleTypeSchema>|null $schemaClass */
        $schemaClass = match ($normalized) {
            'array' => ArraySchema::class,
            'bool', 'boolean' => BooleanSchema::class,
            'float', 'double' => NumberSchema::class,
            'int', 'integer' => IntegerSchema::class,
            'null' => NullSchema::class,
            'object' => ObjectSchema::class,
            'string' => StringSchema::class,
            default => null,
        };

        if (! is_null($schemaClass)) {
            return $schemaClass::make()->applyType()->tree($tree);
        }

        // Best-effort: if token is a class-string of a Data object, transform it
        if (class_exists($typeToken) && is_subclass_of($typeToken, \Spatie\LaravelData\Data::class)) {
            return TransformDataClassToSchema::run($typeToken, $tree);
        }

        return null;
    }
}
