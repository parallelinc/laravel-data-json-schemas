<?php

namespace BasilLangevin\LaravelDataSchemas\Actions;

use BasilLangevin\LaravelDataSchemas\Actions\Concerns\Runnable;
use BasilLangevin\LaravelDataSchemas\Schemas\ArraySchema;
use BasilLangevin\LaravelDataSchemas\Schemas\BooleanSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\Contracts\Schema;
use BasilLangevin\LaravelDataSchemas\Schemas\IntegerSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\NumberSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\ObjectSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\StringSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\UnionSchema;
use ReflectionNamedType;
use ReflectionUnionType;

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
            enum_exists($name) => $this->getEnumSchemaClass($name),
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
