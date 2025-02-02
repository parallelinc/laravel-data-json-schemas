<?php

namespace BasilLangevin\LaravelDataSchemas\Actions;

use BasilLangevin\LaravelDataSchemas\Actions\Concerns\Runnable;
use BasilLangevin\LaravelDataSchemas\Schemas\Contracts\Schema;
use BasilLangevin\LaravelDataSchemas\Schemas\ObjectSchema;
use BasilLangevin\LaravelDataSchemas\Support\ClassWrapper;
use BasilLangevin\LaravelDataSchemas\Support\PropertyWrapper;
use BasilLangevin\LaravelDataSchemas\Support\SchemaTree;

class ApplyPropertiesToDataObjectSchema
{
    /** @use Runnable<ObjectSchema> */
    use Runnable;

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
