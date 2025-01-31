<?php

namespace BasilLangevin\LaravelDataSchemas\Actions;

use BasilLangevin\LaravelDataSchemas\Actions\Concerns\Runnable;
use BasilLangevin\LaravelDataSchemas\Schemas\ObjectSchema;
use BasilLangevin\LaravelDataSchemas\Support\ClassWrapper;
use BasilLangevin\LaravelDataSchemas\Support\PropertyWrapper;
use Spatie\LaravelData\Attributes\Validation\Present;
use Spatie\LaravelData\Attributes\Validation\Required;

class ApplyRequiredToDataObjectSchema
{
    use Runnable;

    public function handle(ObjectSchema $schema, ClassWrapper $class): ObjectSchema
    {
        $required = $this->getRequired($class);

        if (empty($required)) {
            return $schema;
        }

        return $schema->required($required);
    }

    /**
     * Get the required properties of the class.
     */
    protected function getRequired(ClassWrapper $class): array
    {
        return collect([
            ...$this->getNotNullProperties($class),
            ...$this->getPresentProperties($class),
            ...$this->getRequiredProperties($class),
        ])
            ->unique()
            ->toArray();
    }

    /**
     * Get the class properties that are not nullable.
     */
    protected function getNotNullProperties(ClassWrapper $class): array
    {
        return $class->properties()
            ->filter(function (PropertyWrapper $property) {
                $type = $property->getType();

                return $type !== null && ! $property->isNullable();
            })
            ->map->getName()
            ->toArray();
    }

    /**
     * Get the class properties that have the Present attribute.
     */
    protected function getPresentProperties(ClassWrapper $class): array
    {
        return $class->properties()
            ->filter(function (PropertyWrapper $property) {
                return $property->hasAttribute(Present::class);
            })
            ->map->getName()
            ->toArray();
    }

    /**
     * Get the class properties that have the Required attribute.
     */
    protected function getRequiredProperties(ClassWrapper $class): array
    {
        return $class->properties()
            ->filter(function (PropertyWrapper $property) {
                return $property->hasAttribute(Required::class);
            })
            ->map->getName()
            ->toArray();
    }
}
