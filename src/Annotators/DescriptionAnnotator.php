<?php

namespace BasilLangevin\LaravelDataSchemas\Annotators;

use BasilLangevin\LaravelDataSchemas\Annotators\Contracts\AnnotatesSchema;
use BasilLangevin\LaravelDataSchemas\Attributes\Description;
use BasilLangevin\LaravelDataSchemas\Schemas\Contracts\Schema;
use BasilLangevin\LaravelDataSchemas\Support\AttributeWrapper;
use BasilLangevin\LaravelDataSchemas\Support\ClassWrapper;
use BasilLangevin\LaravelDataSchemas\Support\Contracts\EntityWrapper;
use BasilLangevin\LaravelDataSchemas\Support\PropertyWrapper;

class DescriptionAnnotator implements AnnotatesSchema
{
    public static function annotateSchema(Schema $schema, EntityWrapper $entity): Schema
    {
        if ($attribute = $entity->getAttribute(Description::class)) {
            /** @var AttributeWrapper $attribute */
            /** @var string $description */
            $description = $attribute->getValue();

            return $schema->description($description);
        }

        $description = null;

        if ($entity instanceof PropertyWrapper) {
            $description = static::getPropertyDescription($entity);
        }

        if ($entity instanceof ClassWrapper) {
            $description = static::getClassDescription($entity);
        }

        if (! $description) {
            return $schema;
        }

        return $schema->description($description);
    }

    /**
     * Get the description for a property.
     */
    protected static function getPropertyDescription(PropertyWrapper $property): ?string
    {
        $propertyDoc = $property->getDocBlock();
        $constructorDoc = $property->getClass()->getConstructorDocBlock();
        $classDoc = $property->getClass()->getDocBlock();

        $name = $property->getName();

        return $propertyDoc?->getParamDescription($name)
            ?? $propertyDoc?->getVarDescription()
            ?? $propertyDoc?->getDescription()
            ?? $propertyDoc?->getSummary()
            ?? $constructorDoc?->getParamDescription($name)
            ?? $classDoc?->getVarDescription($name)
            ?? null;
    }

    /**
     * Get the description for a class.
     */
    protected static function getClassDescription(ClassWrapper $class): ?string
    {
        $docBlock = $class->getDocBlock();

        return $docBlock?->getDescription()
            ?? $docBlock?->getSummary()
            ?? null;
    }
}
