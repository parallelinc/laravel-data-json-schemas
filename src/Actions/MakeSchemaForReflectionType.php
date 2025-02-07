<?php

namespace BasilLangevin\LaravelDataJsonSchemas\Actions;

use BackedEnum;
use BasilLangevin\LaravelDataJsonSchemas\Actions\Concerns\Runnable;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\ArraySchema;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\BooleanSchema;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\Contracts\Schema;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\IntegerSchema;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\NullSchema;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\NumberSchema;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\ObjectSchema;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\StringSchema;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\UnionSchema;
use DateTimeInterface;
use Illuminate\Support\Collection;
use ReflectionNamedType;
use ReflectionType;
use ReflectionUnionType;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class MakeSchemaForReflectionType
{
    /** @use Runnable<array{ReflectionType}, Schema> */
    use Runnable;

    public function __construct(protected bool $unionNullableTypes = true) {}

    /**
     * Make a Schema object based on the type of a property.
     */
    public function handle(ReflectionType $type): Schema
    {
        return $this->getSchemaClass($type)::make();
    }

    /**
     * Get the appropriate Schema class for a reflection type.
     *
     * @return class-string<Schema>
     */
    protected function getSchemaClass(ReflectionType $type): string
    {
        if ($type instanceof ReflectionUnionType) {
            return UnionSchema::class;
        }

        if (! $type instanceof ReflectionNamedType) {
            throw new \Exception('JSON Schema transformation is not supported for intersection types.');
        }

        $name = $type->getName();

        if ($type->allowsNull() && $name !== 'null' && $this->unionNullableTypes) {
            return UnionSchema::class;
        }

        if (enum_exists($name)) {
            /** @var class-string<BackedEnum> $name */
            return $this->getEnumSchemaClass($name);
        }

        return match (true) {
            $name === 'array' => ArraySchema::class,
            $name === 'bool' => BooleanSchema::class,
            $name === 'float' => NumberSchema::class,
            $name === 'int' => IntegerSchema::class,
            $name === 'null' => NullSchema::class,
            $name === 'string' => StringSchema::class,
            $name === 'object' => ObjectSchema::class,

            $name === 'DateTimeInterface' => StringSchema::class,
            is_subclass_of($name, DateTimeInterface::class) => StringSchema::class,

            $name === Collection::class => ArraySchema::class,
            is_subclass_of($name, Collection::class) => ArraySchema::class,
            is_subclass_of($name, DataCollection::class) => ArraySchema::class,

            is_subclass_of($name, Data::class) => ObjectSchema::class,

            default => throw new \Exception("JSON Schema transformation is not supported for the \"{$name}\" type."),
        };
    }

    /**
     * Get the Schema class for an enum based on its backing type.
     *
     * @param  class-string<BackedEnum>  $enum
     * @return class-string<Schema>
     */
    protected function getEnumSchemaClass(string $enum): string
    {
        $reflector = new \ReflectionEnum($enum);
        if (! $reflector->isBacked()) {
            throw new \Exception("Enum \"{$enum}\" is not a backed enum.");
        }

        $type = $reflector->getBackingType()->getName();

        return $type === 'int'
            ? IntegerSchema::class
            : StringSchema::class;
    }
}
