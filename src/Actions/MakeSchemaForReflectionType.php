<?php

namespace BasilLangevin\LaravelDataSchemas\Actions;

use DateTimeInterface;
use ReflectionNamedType;
use ReflectionUnionType;
use BasilLangevin\LaravelDataSchemas\Schemas\NullSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\ArraySchema;
use BasilLangevin\LaravelDataSchemas\Schemas\UnionSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\NumberSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\ObjectSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\StringSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\BooleanSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\IntegerSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\Contracts\Schema;
use BasilLangevin\LaravelDataSchemas\Actions\Concerns\Runnable;

class MakeSchemaForReflectionType
{
    use Runnable;

    public function handle(ReflectionNamedType|ReflectionUnionType $type, string $name = ''): Schema
    {
        return $this->getSchemaClass($type)::make($name);
    }

    protected function getSchemaClass(ReflectionNamedType|ReflectionUnionType $type): string
    {
        if ($type instanceof ReflectionUnionType) {
            return UnionSchema::class;
        }

        $name = $type->getName();

        return match (true) {
            $name === 'string' => StringSchema::class,
            $name === 'float' => NumberSchema::class,
            $name === 'int' => IntegerSchema::class,
            $name === 'bool' => BooleanSchema::class,
            $name === 'array' => ArraySchema::class,
            $name === 'object' => ObjectSchema::class,
            $name === 'null' => NullSchema::class,
            enum_exists($name) => $this->getEnumSchemaClass($name),
            $name === 'DateTimeInterface' => StringSchema::class,
            is_subclass_of($name, DateTimeInterface::class) => StringSchema::class,
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
