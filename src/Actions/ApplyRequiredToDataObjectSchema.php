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
    /** @use Runnable<array{ObjectSchema, ClassWrapper}, ObjectSchema> */
    use Runnable;

    /**
     * Add each of the Data object's required properties to the "required" keyword.
     */
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
     *
     * @return array<string>
     */
    protected function getRequired(ClassWrapper $class): array
    {
        /** @var array<string> */
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
     *
     * @return array<string>
     */
    protected function getNotNullProperties(ClassWrapper $class): array
    {
        /** @var array<string> */
        return $class->properties()
            ->filter(fn (PropertyWrapper $property) => ! $property->isNullable())
            ->map->getName()
            ->toArray();
    }

    /**
     * Get the class properties that have the Present attribute.
     *
     * @return array<string>
     */
    protected function getPresentProperties(ClassWrapper $class): array
    {
        /** @var array<string> */
        return $class->properties()
            ->filter(function (PropertyWrapper $property) {
                return $property->hasAttribute(Present::class);
            })
            ->map->getName()
            ->toArray();
    }

    /**
     * Get the class properties that have the Required attribute.
     *
     * @return array<string>
     */
    protected function getRequiredProperties(ClassWrapper $class): array
    {
        /** @var array<string> */
        return $class->properties()
            ->filter(function (PropertyWrapper $property) {
                return $property->hasAttribute(Required::class);
            })
            ->map->getName()
            ->toArray();
    }
}
