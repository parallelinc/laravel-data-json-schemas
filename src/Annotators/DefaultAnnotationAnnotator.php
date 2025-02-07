<?php

namespace BasilLangevin\LaravelDataJsonSchemas\Annotators;

use BasilLangevin\LaravelDataJsonSchemas\Annotators\Contracts\AnnotatesSchema;
use BasilLangevin\LaravelDataJsonSchemas\Exceptions\KeywordNotSetException;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\Contracts\Schema;
use BasilLangevin\LaravelDataJsonSchemas\Support\Contracts\EntityWrapper;
use BasilLangevin\LaravelDataJsonSchemas\Support\PropertyWrapper;
use ReflectionParameter;

class DefaultAnnotationAnnotator implements AnnotatesSchema
{
    /**
     * Set the Schema's "default" keyword to the default value of the property.
     */
    public static function annotateSchema(Schema $schema, EntityWrapper $entity): Schema
    {
        if (! $entity instanceof PropertyWrapper) {
            return $schema;
        }

        try {
            return $schema->default(self::getDefaultValue($entity));
        } catch (KeywordNotSetException $e) {
            return $schema;
        }
    }

    /**
     * Get the default value for the property.
     */
    protected static function getDefaultValue(PropertyWrapper $property): mixed
    {
        if ($property->hasDefaultValue()) {
            return $property->getDefaultValue();
        }

        $parameter = self::getConstructorParameter($property);

        if (! $parameter || ! $parameter->isOptional()) {
            throw new KeywordNotSetException;
        }

        return $parameter->getDefaultValue() ?? null;
    }

    /**
     * Get the constructor parameter that matches the property name.
     */
    protected static function getConstructorParameter(
        PropertyWrapper $property,
    ): ?ReflectionParameter {
        $class = $property->getClass();

        if (! $class->hasConstructor()) {
            return null;
        }

        return collect($class->getConstructor()->getParameters())
            ->first(function (ReflectionParameter $parameter) use ($property) {
                return $parameter->getName() === $property->getName();
            }) ?? null;
    }
}
