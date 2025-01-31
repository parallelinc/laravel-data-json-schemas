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
use BasilLangevin\LaravelDataSchemas\Support\PropertyWrapper;
use BasilLangevin\LaravelDataSchemas\Support\SchemaTree;
use BasilLangevin\LaravelDataSchemas\Tests\Integration\DataClasses\VehicleData;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;

covers(ApplyArrayItemsToSchema::class);

beforeEach(function () {
    $this->personSchema = TransformDataClassToSchema::run(VehicleData::class)->toArray();
    $this->tree = app(SchemaTree::class);
    $this->schema = ArraySchema::make()->tree($this->tree);
});

it('can apply the items keyword for a property with a var annotation', function () {
    class ApplyArrayItemsForVarAnnotatedArrayToSchemaTest extends Data
    {
        public function __construct(
            /** @var array<int, VehicleData> */
            public array $annotatedArrayProperty,
        ) {}
    }

    $property = PropertyWrapper::make(ApplyArrayItemsForVarAnnotatedArrayToSchemaTest::class, 'annotatedArrayProperty');

    $schema = ApplyArrayItemsToSchema::run($this->schema, $property, $this->tree);

    expect($schema->toArray())->toEqual([
        'items' => $this->personSchema,
    ]);
});

it('can apply the items keyword for a property with a var annotation on the class', function () {
    /** @var array<int, VehicleData> $annotatedArrayProperty */
    class ApplyArrayItemsForVarAnnotatedArrayOnClassTest extends Data
    {
        public function __construct(
            public array $annotatedArrayProperty,
        ) {}
    }

    $property = PropertyWrapper::make(ApplyArrayItemsForVarAnnotatedArrayOnClassTest::class, 'annotatedArrayProperty');

    $schema = ApplyArrayItemsToSchema::run($this->schema, $property, $this->tree);

    expect($schema->toArray())->toEqual([
        'items' => $this->personSchema,
    ]);
});

it('can apply the items keyword for a property with a param annotation', function () {
    class ApplyArrayItemsForParamAnnotatedArrayToSchemaTest extends Data
    {
        public function __construct(
            /** @param array<int, VehicleData> $annotatedArrayProperty */
            public array $annotatedArrayProperty,
        ) {}
    }

    $property = PropertyWrapper::make(ApplyArrayItemsForParamAnnotatedArrayToSchemaTest::class, 'annotatedArrayProperty');

    $schema = ApplyArrayItemsToSchema::run($this->schema, $property, $this->tree);

    expect($schema->toArray())->toEqual([
        'items' => $this->personSchema,
    ]);
});

it('can apply the items keyword for a property with a DataCollectionOf attribute', function () {
    class ApplyArrayItemsForDataCollectionOfAttributeTest extends Data
    {
        public function __construct(
            #[DataCollectionOf(VehicleData::class)]
            public array $annotatedArrayProperty,
        ) {}
    }

    $property = PropertyWrapper::make(ApplyArrayItemsForDataCollectionOfAttributeTest::class, 'annotatedArrayProperty');

    $schema = ApplyArrayItemsToSchema::run($this->schema, $property, $this->tree);

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

    $schema = ApplyArrayItemsToSchema::run($this->schema, $property, $this->tree);

    expect($schema->toArray())->toEqual([
        'items' => $subschema::make()->applyType()->tree($this->tree)->toArray(),
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

    $schema = ApplyArrayItemsToSchema::run($this->schema, $property, $this->tree);

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

    $schema = ApplyArrayItemsToSchema::run($this->schema, $property, $this->tree);

    expect($schema->toArray())->toEqual([]);
});
