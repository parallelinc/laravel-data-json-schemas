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
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

covers(MakeSchemaForReflectionType::class);

class MakeSchemaForReflectionTypeCollectionInheritance extends Collection {}

class MakeSchemaForReflectionTypeDataCollectionInheritance extends DataCollection {}

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

        public DateTimeInterface $dateTimeProperty,
        public CarbonInterface $carbonInterfaceProperty,
        public Carbon $carbonProperty,

        public Collection $collectionProperty,
        public MakeSchemaForReflectionTypeCollectionInheritance $collectionInheritanceProperty,
        public MakeSchemaForReflectionTypeDataCollectionInheritance $dataCollectionProperty,

        public ?string $nullableProperty,
        public string|int $unionProperty,
    ) {}
}

it('creates the correct Schema type from a Data class property', function ($property, $schemaType) {
    $wrapper = PropertyWrapper::make(MakeSchemaForReflectionTypeTest::class, $property)->getReflectionType();
    $schema = MakeSchemaForReflectionType::run($wrapper, $property);

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
    ['dateTimeProperty', StringSchema::class],
    ['carbonProperty', StringSchema::class],
    ['collectionProperty', ArraySchema::class],
    ['collectionInheritanceProperty', ArraySchema::class],
    ['dataCollectionProperty', ArraySchema::class],
    ['nullableProperty', UnionSchema::class],
    ['unionProperty', UnionSchema::class],
]);

it('makes a UnionSchema for a nullable type by default', function () {
    $wrapper = PropertyWrapper::make(MakeSchemaForReflectionTypeTest::class, 'nullableProperty')->getReflectionType();
    $schema = MakeSchemaForReflectionType::run($wrapper, 'nullableProperty');

    expect($schema)->toBeInstanceOf(UnionSchema::class);
});

it('does not make a UnionSchema for a nullable type if the unionNullableTypes option is false', function () {
    $wrapper = PropertyWrapper::make(MakeSchemaForReflectionTypeTest::class, 'nullableProperty')->getReflectionType();
    $action = new MakeSchemaForReflectionType(unionNullableTypes: false);
    $schema = $action->handle($wrapper, 'nullableProperty');

    expect($schema)->not->toBeInstanceOf(UnionSchema::class);
});
