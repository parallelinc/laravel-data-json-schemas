<?php

use BasilLangevin\LaravelDataSchemas\Actions\ApplyAnnotationsToSchema;
use BasilLangevin\LaravelDataSchemas\Actions\ApplyEnumToSchema;
use BasilLangevin\LaravelDataSchemas\Actions\ApplyRuleConfigurationsToSchema;
use BasilLangevin\LaravelDataSchemas\Actions\ApplyTypeToSchema;
use BasilLangevin\LaravelDataSchemas\Actions\TransformPropertyToSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\StringSchema;
use BasilLangevin\LaravelDataSchemas\Support\PropertyWrapper;
use BasilLangevin\LaravelDataSchemas\Tests\Support\Enums\TestIntegerEnum;
use BasilLangevin\LaravelDataSchemas\Tests\Support\Enums\TestStringEnum;
use Spatie\LaravelData\Data;

covers(TransformPropertyToSchema::class);

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
    ) {}
}

it('calls the ApplyTypeToSchema action', function () {
    $property = PropertyWrapper::make(PropertyTransformActionTest::class, 'stringProperty');

    $mock = $this->mock(ApplyTypeToSchema::class);

    $mock->shouldReceive('handle')->once()
        ->andReturn(StringSchema::make('stringProperty'));

    $action = new TransformPropertyToSchema;
    $action->handle($property);
});

it('calls the ApplyEnumToSchema action', function () {
    $property = PropertyWrapper::make(PropertyTransformActionTest::class, 'stringEnumProperty');

    $mock = $this->mock(ApplyEnumToSchema::class);

    $mock->shouldReceive('handle')->once()
        ->andReturn(StringSchema::make('stringEnumProperty'));

    $action = new TransformPropertyToSchema;
    $action->handle($property);
});

it('calls the ApplyAnnotationsToSchema action', function () {
    $property = PropertyWrapper::make(PropertyTransformActionTest::class, 'stringProperty');

    $mock = $this->mock(ApplyAnnotationsToSchema::class);

    $mock->shouldReceive('handle')->once()
        ->andReturn(StringSchema::make('stringProperty'));

    $action = new TransformPropertyToSchema;
    $action->handle($property);
});

it('calls the ApplyRuleConfigurationsToSchema action', function () {
    $property = PropertyWrapper::make(PropertyTransformActionTest::class, 'stringProperty');

    $mock = $this->mock(ApplyRuleConfigurationsToSchema::class);

    $mock->shouldReceive('handle')->once()
        ->andReturn(StringSchema::make('stringProperty'));

    $action = new TransformPropertyToSchema;
    $action->handle($property);
});
