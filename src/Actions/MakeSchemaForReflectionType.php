<?php

namespace BasilLangevin\LaravelDataSchemas\Actions;

use BasilLangevin\LaravelDataSchemas\Actions\Concerns\Runnable;
use BasilLangevin\LaravelDataSchemas\Schemas\ArraySchema;
use BasilLangevin\LaravelDataSchemas\Schemas\BooleanSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\Contracts\Schema;
use BasilLangevin\LaravelDataSchemas\Schemas\IntegerSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\NullSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\NumberSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\ObjectSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\StringSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\UnionSchema;
use DateTimeInterface;
use Illuminate\Support\Collection;
use ReflectionNamedType;
use ReflectionUnionType;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class MakeSchemaForReflectionType
{
    use Runnable;

    public function __construct(protected bool $unionNullableTypes = true) {}

    public function handle(ReflectionNamedType|ReflectionUnionType $type): Schema
    {
        return $this->getSchemaClass($type)::make();
    }

    protected function getSchemaClass(ReflectionNamedType|ReflectionUnionType $type): string
    {
        if ($type instanceof ReflectionUnionType) {
            return UnionSchema::class;
        }

        $name = $type->getName();

        if ($type->allowsNull() && $name !== 'null' && $this->unionNullableTypes) {
            return UnionSchema::class;
        }

        return match (true) {
            $name === 'array' => ArraySchema::class,
            $name === 'bool' => BooleanSchema::class,
            $name === 'float' => NumberSchema::class,
            $name === 'int' => IntegerSchema::class,
            $name === 'null' => NullSchema::class,
            $name === 'string' => StringSchema::class,
            $name === 'object' => ObjectSchema::class,

            enum_exists($name) => $this->getEnumSchemaClass($name),

            $name === 'DateTimeInterface' => StringSchema::class,
            is_subclass_of($name, DateTimeInterface::class) => StringSchema::class,

            $name === Collection::class => ArraySchema::class,
            is_subclass_of($name, Collection::class) => ArraySchema::class,
            is_subclass_of($name, DataCollection::class) => ArraySchema::class,

            is_subclass_of($name, Data::class) => ObjectSchema::class,
        };
    }

    protected function getEnumSchemaClass(string $enum): string
    {
        $reflector = new \ReflectionEnum($enum);
        $type = $reflector->getBackingType()->getName();

        return match ($type) {
            'string' => StringSchema::class,
            'int' => IntegerSchema::class,
        };
    }
}
