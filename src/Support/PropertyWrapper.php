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

class PropertyWrapper implements EntityWrapper
{
    use AccessesAttributes;
    use AccessesDocBlock;

    public function __construct(protected ReflectionProperty $property) {}

    /**
     * Create a new property wrapper from a reflection property.
     */
    public static function make(string $className, string $propertyName): self
    {
        return new self(new ReflectionProperty($className, $propertyName));
    }

    public function getType(): ReflectionNamedType|ReflectionUnionType
    {
        return $this->property->getType();
    }

    /**
     * Get the types of the property as a collection.
     *
     * @return \Illuminate\Support\Collection<int, ReflectionNamedType>
     */
    public function getTypes(): Collection
    {
        if ($this->isUnion()) {
            return collect($this->getType()->getTypes());
        }

        return collect([$this->getType()]);
    }

    public function getTypeNames(): Collection
    {
        return $this->getTypes()->map->getName();
    }

    /**
     * Determine if the property has a type.
     */
    public function hasType(string $type): bool
    {
        return match ($type) {
            '*' => true,
            'array' => $this->isArray(),
            'boolean' => $this->isBoolean(),
            'integer' => $this->isInteger(),
            'number' => $this->isNumber(),
            'object' => $this->isObject(),
            'string' => $this->isString(),
            default => false,
        };
    }

    protected function isUnion(): bool
    {
        return $this->getType() instanceof ReflectionUnionType;
    }

    /**
     * Determine if the reflected property is an array.
     */
    public function isArray(): bool
    {
        if ($this->isUnion()) {
            return false;
        }

        return $this->getType()->getName() === 'array';
    }

    /**
     * Determine if the reflected property is a boolean.
     */
    public function isBoolean(): bool
    {
        if ($this->isUnion()) {
            return false;
        }

        return $this->getType()->getName() === 'bool';
    }

    public function isDateTime(): bool
    {
        if ($this->isUnion()) {
            return false;
        }

        $typeName = $this->getType()->getName();

        if (is_subclass_of($typeName, DateTimeInterface::class)) {
            return true;
        }

        return $typeName === 'DateTimeInterface';
    }

    /**
     * Determine if the reflected property is an enum.
     */
    public function isEnum(): bool
    {
        if ($this->isUnion()) {
            return false;
        }

        return enum_exists($this->getType()->getName());
    }

    /**
     * Determine if the reflected property is a float.
     */
    public function isFloat(): bool
    {
        if ($this->isUnion()) {
            return false;
        }

        return $this->getType()->getName() === 'float';
    }

    /**
     * Determine if the reflected property is an integer.
     */
    public function isInteger(): bool
    {
        if ($this->isUnion()) {
            return false;
        }

        return $this->getType()->getName() === 'int';
    }

    /**
     * Determine if the reflected property is a number.
     */
    public function isNumber(): bool
    {
        if ($this->isUnion()) {
            return false;
        }

        return $this->getType()->getName() === 'int' || $this->getType()->getName() === 'float';
    }

    /**
     * Determine if the reflected property is an object.
     */
    public function isObject(): bool
    {
        if ($this->isUnion()) {
            return false;
        }

        return $this->getType()->getName() === 'object';
    }

    /**
     * Determine if the reflected property is a string.
     */
    public function isString(): bool
    {
        if ($this->isUnion()) {
            return false;
        }

        return $this->getType()->getName() === 'string';
    }

    /**
     * Determine if the reflected property is nullable.
     */
    public function isNullable(): bool
    {
        return $this->getTypes()->contains(fn (ReflectionNamedType $type) => $type->allowsNull());
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
