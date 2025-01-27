<?php

use BasilLangevin\LaravelDataSchemas\Actions\ApplyAnnotationsToSchema;
use BasilLangevin\LaravelDataSchemas\Actions\ApplyPropertiesToDataObjectSchema;
use BasilLangevin\LaravelDataSchemas\Actions\ApplyRequiredToDataObjectSchema;
use BasilLangevin\LaravelDataSchemas\Actions\ApplyRuleConfigurationsToSchema;
use BasilLangevin\LaravelDataSchemas\Actions\TransformDataClassToSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\ObjectSchema;
use BasilLangevin\LaravelDataSchemas\Support\ClassWrapper;
use Spatie\LaravelData\Data;

covers(TransformDataClassToSchema::class);

class PropertylessDataClassTransformActionTest extends Data {}

class DataClassTransformActionTest extends Data
{
    public function __construct(
        public string $property
    ) {}
}

it('creates an ObjectSchema from a Data class', function () {
    $schema = TransformDataClassToSchema::run(ClassWrapper::make(DataClassTransformActionTest::class));

    expect($schema)->toBeInstanceOf(ObjectSchema::class);
});

it('calls the ApplyAnnotationsToSchema action', function () {
    $class = ClassWrapper::make(PropertylessDataClassTransformActionTest::class);

    $mock = $this->mock(ApplyAnnotationsToSchema::class);

    $mock->shouldReceive('handle')->once()
        ->andReturn(ObjectSchema::make('Test'));

    $action = new TransformDataClassToSchema;
    $action->handle($class);
});

it('calls the ApplyRuleConfigurationsToSchema action', function () {
    $class = ClassWrapper::make(PropertylessDataClassTransformActionTest::class);

    $mock = $this->mock(ApplyRuleConfigurationsToSchema::class);

    $mock->shouldReceive('handle')->once()
        ->andReturn(ObjectSchema::make('Test'));

    $action = new TransformDataClassToSchema;
    $action->handle($class);
});

it('calls the ApplyPropertiesToDataObjectSchema action', function () {
    $class = ClassWrapper::make(DataClassTransformActionTest::class);

    $mock = $this->mock(ApplyPropertiesToDataObjectSchema::class);

    $mock->shouldReceive('handle')->once()->andReturn(ObjectSchema::make('Test'));

    $action = new TransformDataClassToSchema;
    $action->handle($class);
});

it('calls the ApplyRequiredToDataObjectSchema action', function () {
    $class = ClassWrapper::make(DataClassTransformActionTest::class);

    $mock = $this->mock(ApplyRequiredToDataObjectSchema::class);

    $mock->shouldReceive('handle')->once()->andReturn(ObjectSchema::make('Test'));

    $action = new TransformDataClassToSchema;
    $action->handle($class);
});
