<?php

use BasilLangevin\LaravelDataSchemas\Actions\ApplyAnnotationsToSchema;
use BasilLangevin\LaravelDataSchemas\Actions\ApplyEnumToSchema;
use BasilLangevin\LaravelDataSchemas\Actions\ApplyPropertiesToDataObjectSchema;
use BasilLangevin\LaravelDataSchemas\Actions\ApplyRequiredToDataObjectSchema;
use BasilLangevin\LaravelDataSchemas\Actions\ApplyRuleConfigurationsToSchema;
use BasilLangevin\LaravelDataSchemas\Actions\SetupSchema;
use BasilLangevin\LaravelDataSchemas\Actions\TransformPropertyToSchema;
use BasilLangevin\LaravelDataSchemas\Enums\Format;
use BasilLangevin\LaravelDataSchemas\Schemas\ObjectSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\StringSchema;
use BasilLangevin\LaravelDataSchemas\Support\PropertyWrapper;
use BasilLangevin\LaravelDataSchemas\Support\SchemaTree;
use BasilLangevin\LaravelDataSchemas\Tests\Integration\DataClasses\PersonData;
use BasilLangevin\LaravelDataSchemas\Tests\Support\Enums\TestIntegerEnum;
use BasilLangevin\LaravelDataSchemas\Tests\Support\Enums\TestStringEnum;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Spatie\LaravelData\Data;

covers(TransformPropertyToSchema::class);

beforeEach(function () {
    $this->tree = app(SchemaTree::class);
});

class PropertyTransformActionTest extends Data
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
        public DateTime $testDateTime,
        public DateTimeInterface $testDateTimeInterface,
        public CarbonInterface $testCarbonInterface,
        public Carbon $testCarbon,
        public PersonData $dataObjectProperty,
    ) {}
}

it('calls the SetupSchema action', function () {
    $property = PropertyWrapper::make(PropertyTransformActionTest::class, 'stringProperty');

    $mock = $this->mock(SetupSchema::class);

    $mock->shouldReceive('handle')->once()
        ->andReturn(StringSchema::make());

    $action = new TransformPropertyToSchema;
    $action->handle($property, $this->tree);
});

it('calls the ApplyEnumToSchema action', function () {
    $property = PropertyWrapper::make(PropertyTransformActionTest::class, 'stringEnumProperty');

    $mock = $this->mock(ApplyEnumToSchema::class);

    $mock->shouldReceive('handle')->once()
        ->andReturn(StringSchema::make());

    $action = new TransformPropertyToSchema;
    $action->handle($property, $this->tree);
});

it('calls the ApplyAnnotationsToSchema action', function () {
    $property = PropertyWrapper::make(PropertyTransformActionTest::class, 'stringProperty');

    $mock = $this->mock(ApplyAnnotationsToSchema::class);

    $mock->shouldReceive('handle')->once()
        ->andReturn(StringSchema::make());

    $action = new TransformPropertyToSchema;
    $action->handle($property, $this->tree);
});

it('calls the ApplyRuleConfigurationsToSchema action', function () {
    $property = PropertyWrapper::make(PropertyTransformActionTest::class, 'stringProperty');

    $mock = $this->mock(ApplyRuleConfigurationsToSchema::class);

    $mock->shouldReceive('handle')->once()
        ->andReturn(StringSchema::make());

    $action = new TransformPropertyToSchema;
    $action->handle($property, $this->tree);
});

it('applies the DateTime format to DateTime properties', function ($property) {
    $property = PropertyWrapper::make(PropertyTransformActionTest::class, $property);

    $action = new TransformPropertyToSchema;
    $schema = $action->handle($property, $this->tree);

    expect($schema->getFormat())->toBe(Format::DateTime);
})->with([
    ['testDateTime'],
    ['testDateTimeInterface'],
    ['testCarbonInterface'],
    ['testCarbon'],
]);

it('calls the ApplyPropertiesToDataObjectSchema action when the property is a data object', function () {
    $property = PropertyWrapper::make(PropertyTransformActionTest::class, 'dataObjectProperty');

    $mock = $this->mock(ApplyPropertiesToDataObjectSchema::class);

    $mock->shouldReceive('handle')->once()
        ->andReturn(ObjectSchema::make());

    $action = new TransformPropertyToSchema;
    $action->handle($property, $this->tree);
});

it('calls the ApplyRequiredToDataObjectSchema action when the property is a data object', function () {
    $property = PropertyWrapper::make(PropertyTransformActionTest::class, 'dataObjectProperty');

    $mock = $this->mock(ApplyRequiredToDataObjectSchema::class);

    $mock->shouldReceive('handle')->once()
        ->andReturn(ObjectSchema::make());

    $action = new TransformPropertyToSchema;
    $action->handle($property, $this->tree);
});
