<?php

namespace BasilLangevin\LaravelDataSchemas\Actions;

use BasilLangevin\LaravelDataSchemas\Actions\Concerns\Runnable;
use BasilLangevin\LaravelDataSchemas\Schemas\Contracts\Schema;
use BasilLangevin\LaravelDataSchemas\Support\ClassWrapper;
use BasilLangevin\LaravelDataSchemas\Support\PropertyWrapper;

class ApplyPropertiesToDataObjectSchema
{
    use Runnable;

    public function handle(Schema $schema, ClassWrapper $class): Schema
    {
        $properties = $this->getProperties($class);

        if (empty($properties)) {
            return $schema;
        }

        return $schema->properties($properties);
    }

    /**
     * Get the properties of the class, transforming each into a Schema object.
     */
    protected function getProperties(ClassWrapper $class): array
    {
        return $class->properties()
            ->map(function (PropertyWrapper $property) {
                return TransformPropertyToSchema::run($property);
            })
            ->all();
    }
}
