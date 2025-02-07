<?php

namespace BasilLangevin\LaravelDataJsonSchemas\Annotators;

use BasilLangevin\LaravelDataJsonSchemas\Annotators\Contracts\AnnotatesSchema;
use BasilLangevin\LaravelDataJsonSchemas\Attributes\Description;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\Contracts\Schema;
use BasilLangevin\LaravelDataJsonSchemas\Support\AttributeWrapper;
use BasilLangevin\LaravelDataJsonSchemas\Support\ClassWrapper;
use BasilLangevin\LaravelDataJsonSchemas\Support\Contracts\EntityWrapper;
use BasilLangevin\LaravelDataJsonSchemas\Support\PropertyWrapper;

class DescriptionAnnotator implements AnnotatesSchema
{
    /**
     * Set the Schema's "description" keyword to the description of the property or class.
     *
     * The description is set in the following order of precedence:
     * 1. The Description attribute.
     * 2. The docblock param/var description.
     * 3. The docblock description.
     * 4. The docblock summary.
     */
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
