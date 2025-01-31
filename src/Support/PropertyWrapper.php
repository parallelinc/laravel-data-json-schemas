<?php

namespace BasilLangevin\LaravelDataSchemas\Support;

use BasilLangevin\LaravelDataSchemas\Support\Concerns\AccessesAttributes;
use BasilLangevin\LaravelDataSchemas\Support\Concerns\AccessesDocBlock;
use BasilLangevin\LaravelDataSchemas\Support\Contracts\EntityWrapper;
use DateTimeInterface;
use Illuminate\Support\Collection;
use ReflectionNamedType;
use ReflectionProperty;
use ReflectionUnionType;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\DataProperty;
use Spatie\LaravelData\Support\DataPropertyType;
use Spatie\LaravelData\Support\Types\CombinationType;
use Spatie\LaravelData\Support\Types\NamedType;
use Spatie\LaravelData\Support\Types\Type;
use Spatie\LaravelData\Support\Types\UnionType;

class PropertyWrapper implements EntityWrapper
{
    use AccessesAttributes;
    use AccessesDocBlock;

    protected ReflectionProperty $property;

    public function __construct(
        protected DataProperty $dataProperty
    ) {
        $this->property = new ReflectionProperty($dataProperty->className, $dataProperty->name);
    }

    /**
     * Create a new property wrapper from a reflection property.
     */
    public static function make(string $className, string $propertyName): self
    {
        return ClassWrapper::make($className)->getProperty($propertyName);
    }

    public function getReflection(): ReflectionProperty
    {
        return $this->property;
    }

    public function getDataProperty(): DataProperty
    {
        return $this->dataProperty;
    }

    public function getDataType(): DataPropertyType
    {
        return $this->dataProperty->type;
    }

    public function getReflectionType(): ReflectionNamedType|ReflectionUnionType
    {
        return $this->property->getType();
    }

    public function getReflectionTypes(): Collection
    {
        $type = $this->getReflectionType();

        if ($type instanceof ReflectionUnionType) {
            return collect($type->getTypes());
        }

        return collect([$type]);
    }

    public function getType(): NamedType|CombinationType
    {
        return $this->getDataType()->type;
    }

    /**
     * Get the types of the property as a collection.
     *
     * @return \Illuminate\Support\Collection<int, NamedType>
     */
    public function getTypes(): Collection
    {
        $type = $this->getType();

        if ($type instanceof UnionType) {
            return collect($type->types);
        }

        return collect([$type]);
    }

    public function getTypeNames(): Collection
    {
        return $this->getTypes()->map->name;
    }

    /**
     * Determine if the property has a type.
     */
    public function hasType(string $type): bool
    {
        return match ($type) {
            '*' => true,
            'array' => $this->hasTypeName('array'),
            'boolean' => $this->hasTypeName('bool'),
            'integer' => $this->hasTypeName('int'),
            'number' => $this->hasTypeName('int') || $this->hasTypeName('float'),
            'object' => $this->hasTypeName('object'),
            'string' => $this->hasTypeName('string'),
            default => false,
        };
    }

    public function hasTypeName(string $type): bool
    {
        if ($this->isUnion()) {
            return false;
        }

        return $this->getType()->name === $type;
    }

    protected function isUnion(): bool
    {
        return $this->getType() instanceof UnionType;
    }

    public function isDateTime(): bool
    {
        if ($this->isUnion()) {
            return false;
        }

        $typeName = $this->getType()->name;

        return is_subclass_of($typeName, DateTimeInterface::class)
            || $typeName === 'DateTimeInterface';
    }

    public function isArray(): bool
    {
        if ($this->isUnion()) {
            return false;
        }

        $kind = $this->getType()->kind;

        return $kind->isDataCollectable() || $kind->isNonDataIteratable();
    }

    /**
     * Determine if the reflected property is an enum.
     */
    public function isEnum(): bool
    {
        if ($this->isUnion()) {
            return false;
        }

        return enum_exists($this->getType()->name);
    }

    /**
     * Determine if the reflected property is a Spatie data object.
     */
    public function isDataObject(): bool
    {
        if ($this->isUnion() || $this->isArray()) {
            return false;
        }

        return is_subclass_of($this->getType()->dataClass, Data::class);
    }

    /**
     * Determine if the reflected property is nullable.
     */
    public function isNullable(): bool
    {
        return $this->getDataType()->isNullable;
    }

    /**
     * Get the name of the data class of the property.
     */
    public function getDataClassName(): string
    {
        return $this->getType()->dataClass;
    }

    /**
     * Get the data class of the property as a ClassWrapper.
     */
    public function getDataClass(): ?ClassWrapper
    {
        if (! $this->isDataObject()) {
            return null;
        }

        return ClassWrapper::make($this->getDataClassName());
    }

    /**
     * Get the name of the property.
     */
    public function getName(): string
    {
        return $this->property->getName();
    }

    /**
     * Determine if the property has a default value.
     */
    public function hasDefaultValue(): bool
    {
        return $this->property->hasDefaultValue();
    }

    /**
     * Get the default value of the property.
     */
    public function getDefaultValue(): mixed
    {
        return $this->property->getDefaultValue();
    }

    /**
     * Get the declaring class of the property as a ClassWrapper.
     */
    public function getClass(): ClassWrapper
    {
        return new ClassWrapper($this->property->getDeclaringClass());
    }

    /**
     * Get the siblings of the property as a collection.
     */
    public function siblings(): Collection
    {
        return collect($this->getClass()->properties())
            ->filter(function (PropertyWrapper $property) {
                return $property->getName() !== $this->getName();
            })
            ->values();
    }

    /**
     * Get the sibling names of the property as a collection.
     */
    public function siblingNames(): Collection
    {
        return $this->siblings()->map->getName();
    }
}
