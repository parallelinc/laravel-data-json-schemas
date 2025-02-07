<?php

namespace BasilLangevin\LaravelDataJsonSchemas\Actions;

use BasilLangevin\LaravelDataJsonSchemas\Actions\Concerns\Runnable;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\Contracts\Schema;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\ObjectSchema;
use BasilLangevin\LaravelDataJsonSchemas\Support\ClassWrapper;
use BasilLangevin\LaravelDataJsonSchemas\Support\PropertyWrapper;
use BasilLangevin\LaravelDataJsonSchemas\Support\SchemaTree;

class ApplyPropertiesToDataObjectSchema
{
    /** @use Runnable<array{ObjectSchema, ClassWrapper, SchemaTree}, ObjectSchema> */
    use Runnable;

    /**
     * Create Schemas for each property and add them to the "properties" keyword.
     */
    public function handle(ObjectSchema $schema, ClassWrapper $class, SchemaTree $tree): ObjectSchema
    {
        $properties = $this->getProperties($class, $tree);

        if (empty($properties)) {
            return $schema;
        }

        return $schema->properties($properties);
    }

    /**
     * Get the properties of the class, transforming each into a Schema object.
     *
     * @return array<string, Schema>
     */
    protected function getProperties(ClassWrapper $class, SchemaTree $tree): array
    {
        return $class->properties()
            ->mapWithKeys(fn (PropertyWrapper $property) => [
                $property->getName() => TransformPropertyToSchema::run($property, $tree),
            ])
            ->all();
    }
}
