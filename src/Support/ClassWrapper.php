<?php

namespace BasilLangevin\LaravelDataSchemas\Support;

use BasilLangevin\LaravelDataSchemas\Support\Concerns\AccessesAttributes;
use BasilLangevin\LaravelDataSchemas\Support\Concerns\AccessesDocBlock;
use BasilLangevin\LaravelDataSchemas\Support\Contracts\EntityWrapper;
use Illuminate\Support\Collection;
use ReflectionClass;
use ReflectionMethod;
use Reflector;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\DataClass;
use Spatie\LaravelData\Support\DataProperty;
use Spatie\LaravelData\Support\Factories\DataClassFactory;

class ClassWrapper implements EntityWrapper
{
    use AccessesAttributes;
    use AccessesDocBlock;

    public function __construct(protected ReflectionClass $class) {}

    /**
     * Create a new class wrapper from a class name.
     */
    public static function make(string $className): self
    {
        return new self(new ReflectionClass($className));
    }

    public function getDataClass(): DataClass
    {
        return app(DataClassFactory::class)->build($this->class);
    }

    /**
     * Get the name of the class.
     */
    public function getName(): string
    {
        return $this->class->getName();
    }

    /**
     * Get the short name of the class.
     */
    public function getShortName(): string
    {
        return $this->class->getShortName();
    }

    /**
     * Check if the class is a data object.
     */
    public function isDataObject(): bool
    {
        return $this->class->isSubclassOf(Data::class);
    }

    /**
     * Get the properties of the reflector as a collection.
     *
     * @return \Illuminate\Support\Collection<int, PropertyWrapper>
     */
    public function properties(): Collection
    {
        return $this->getDataClass()->properties
            ->map(function (DataProperty $property) {
                return new PropertyWrapper($property);
            })
            ->values();
    }

    public function getProperty(string $propertyName): PropertyWrapper
    {
        return $this->properties()->first(fn (PropertyWrapper $property) => $property->getName() === $propertyName);
    }

    /**
     * Check if the class has a constructor.
     */
    public function hasConstructor(): bool
    {
        return $this->class->hasMethod('__construct');
    }

    /**
     * Get the constructor of the class.
     */
    public function getConstructor(): ?ReflectionMethod
    {
        return $this->class->getMethod('__construct');
    }

    /**
     * Get the doc block for the class' __construct method.
     */
    public function getConstructorDocBlock(): ?DocBlockParser
    {
        if (! $this->hasConstructor()) {
            return null;
        }

        return DocBlockParser::make($this->getConstructor()->getDocComment());
    }
}
