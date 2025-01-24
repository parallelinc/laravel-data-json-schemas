<?php

use BasilLangevin\LaravelDataSchemas\Actions\MakeSchemaForReflectionType;
use BasilLangevin\LaravelDataSchemas\Schemas\ArraySchema;
use BasilLangevin\LaravelDataSchemas\Schemas\BooleanSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\IntegerSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\NumberSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\ObjectSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\StringSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\UnionSchema;
use BasilLangevin\LaravelDataSchemas\Support\PropertyWrapper;
use BasilLangevin\LaravelDataSchemas\Tests\Support\Enums\TestIntegerEnum;
use BasilLangevin\LaravelDataSchemas\Tests\Support\Enums\TestStringEnum;
use Spatie\LaravelData\Data;

covers(MakeSchemaForReflectionType::class);

class MakeSchemaForReflectionTypeTest extends Data
{
    public function __construct(
        public array $arrayProperty,
        public bool $boolProperty,
        public float $floatProperty,
        public int $intProperty,
        public object $objectProperty,
        public string $stringProperty,
        public TestStringEnum $stringEnumProperty,
        public TestIntegerEnum $intEnumProperty,
        public string|int $unionProperty,
    ) {}
}

it('creates the correct Schema type from a Data class property', function ($property, $schemaType) {
    $schema = MakeSchemaForReflectionType::run(PropertyWrapper::make(MakeSchemaForReflectionTypeTest::class, $property)->getType(), $property);

    expect($schema)->toBeInstanceOf($schemaType);
    expect($schema->getName())->toBe($property);
})->with([
    ['arrayProperty', ArraySchema::class],
    ['boolProperty', BooleanSchema::class],
    ['floatProperty', NumberSchema::class],
    ['intProperty', IntegerSchema::class],
    ['objectProperty', ObjectSchema::class],
    ['stringProperty', StringSchema::class],
    ['stringEnumProperty', StringSchema::class],
    ['intEnumProperty', IntegerSchema::class],
    ['unionProperty', UnionSchema::class],
]);
