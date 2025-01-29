<?php

use BasilLangevin\LaravelDataSchemas\Actions\ApplyArrayItemsToSchema;
use BasilLangevin\LaravelDataSchemas\Actions\TransformDataClassToSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\ArraySchema;
use BasilLangevin\LaravelDataSchemas\Schemas\BooleanSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\IntegerSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\NullSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\NumberSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\ObjectSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\StringSchema;
use BasilLangevin\LaravelDataSchemas\Support\ClassWrapper;
use BasilLangevin\LaravelDataSchemas\Support\PropertyWrapper;
use BasilLangevin\LaravelDataSchemas\Tests\Integration\DataClasses\PersonData;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;

covers(ApplyArrayItemsToSchema::class);

beforeEach(function () {
    $class = ClassWrapper::make(PersonData::class);
    $this->personSchema = TransformDataClassToSchema::run($class)->toArray();
});

it('can apply the items keyword for a property with a var annotation', function () {
    class ApplyArrayItemsForVarAnnotatedArrayToSchemaTest extends Data
    {
        public function __construct(
            /** @var array<int, PersonData> */
            public array $annotatedArrayProperty,
        ) {}
    }

    $property = PropertyWrapper::make(ApplyArrayItemsForVarAnnotatedArrayToSchemaTest::class, 'annotatedArrayProperty');

    $schema = ArraySchema::make();

    $schema = ApplyArrayItemsToSchema::run($schema, $property);

    expect($schema->toArray())->toEqual([
        'items' => $this->personSchema,
    ]);
});

it('can apply the items keyword for a property with a var annotation on the class', function () {
    /** @var array<int, PersonData> $annotatedArrayProperty */
    class ApplyArrayItemsForVarAnnotatedArrayOnClassTest extends Data
    {
        public function __construct(
            public array $annotatedArrayProperty,
        ) {}
    }

    $property = PropertyWrapper::make(ApplyArrayItemsForVarAnnotatedArrayOnClassTest::class, 'annotatedArrayProperty');

    $schema = ArraySchema::make();

    $schema = ApplyArrayItemsToSchema::run($schema, $property);

    expect($schema->toArray())->toEqual([
        'items' => $this->personSchema,
    ]);
});

it('can apply the items keyword for a property with a param annotation', function () {
    class ApplyArrayItemsForParamAnnotatedArrayToSchemaTest extends Data
    {
        public function __construct(
            /** @param array<int, PersonData> $annotatedArrayProperty */
            public array $annotatedArrayProperty,
        ) {}
    }

    $property = PropertyWrapper::make(ApplyArrayItemsForParamAnnotatedArrayToSchemaTest::class, 'annotatedArrayProperty');

    $schema = ArraySchema::make();

    $schema = ApplyArrayItemsToSchema::run($schema, $property);

    expect($schema->toArray())->toEqual([
        'items' => $this->personSchema,
    ]);
});

it('can apply the items keyword for a property with a DataCollectionOf attribute', function () {
    class ApplyArrayItemsForDataCollectionOfAttributeTest extends Data
    {
        public function __construct(
            #[DataCollectionOf(PersonData::class)]
            public array $annotatedArrayProperty,
        ) {}
    }

    $property = PropertyWrapper::make(ApplyArrayItemsForDataCollectionOfAttributeTest::class, 'annotatedArrayProperty');

    $schema = ArraySchema::make();

    $schema = ApplyArrayItemsToSchema::run($schema, $property);

    expect($schema->toArray())->toEqual([
        'items' => $this->personSchema,
    ]);
});

it('can apply the items keyword for a property with an iterable array annotation', function ($type, $subschema) {
    $classDefinition = <<<PHP
    class ApplyArrayItemsForIterableArrayToSchemaTest_{$type} extends \Spatie\LaravelData\Data
    {
        public function __construct(
            /** @var array<int, $type> */
            public array \$annotatedArrayProperty,
        ) {
        }
    }
    PHP;

    eval($classDefinition);

    $property = PropertyWrapper::make("ApplyArrayItemsForIterableArrayToSchemaTest_{$type}", 'annotatedArrayProperty');

    $schema = ArraySchema::make();

    $schema = ApplyArrayItemsToSchema::run($schema, $property);

    expect($schema->toArray())->toEqual([
        'items' => $subschema::make()->applyType()->toArray(),
    ]);
})->with([
    ['array', ArraySchema::class],
    ['bool', BooleanSchema::class],
    ['float', NumberSchema::class],
    ['int', IntegerSchema::class],
    ['null', NullSchema::class],
    ['string', StringSchema::class],
    ['object', ObjectSchema::class],
]);

it('does not apply the items keyword for a property with no array annotation', function () {
    class ApplyArrayItemsForNonIterableTypeTest extends Data
    {
        public function __construct(
            public array $nonIterableProperty,
        ) {}
    }

    $property = PropertyWrapper::make(ApplyArrayItemsForNonIterableTypeTest::class, 'nonIterableProperty');

    $schema = ArraySchema::make();

    $schema = ApplyArrayItemsToSchema::run($schema, $property);

    expect($schema->toArray())->toEqual([]);
});

it('does not apply the items keyword for a property with a mixed iterable type', function () {
    class ApplyArrayItemsForMixedIterableTypeTest extends Data
    {
        public function __construct(
            /** @var array<int, mixed> */
            public array $mixedIterableProperty,
        ) {}
    }

    $property = PropertyWrapper::make(ApplyArrayItemsForMixedIterableTypeTest::class, 'mixedIterableProperty');

    $schema = ArraySchema::make();

    $schema = ApplyArrayItemsToSchema::run($schema, $property);

    expect($schema->toArray())->toEqual([]);
});
