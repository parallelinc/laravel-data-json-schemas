<?php

use BasilLangevin\LaravelDataSchemas\Actions\ApplyAnnotationsToSchema;
use BasilLangevin\LaravelDataSchemas\Actions\ApplyPropertiesToDataObjectSchema;
use BasilLangevin\LaravelDataSchemas\Actions\ApplyRequiredToDataObjectSchema;
use BasilLangevin\LaravelDataSchemas\Actions\ApplyRuleConfigurationsToSchema;
use BasilLangevin\LaravelDataSchemas\Actions\TransformDataClassToSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\ObjectSchema;
use BasilLangevin\LaravelDataSchemas\Support\DataClassSchemaRegistry;
use BasilLangevin\LaravelDataSchemas\Support\SchemaTree;
use Spatie\LaravelData\Data;

covers(TransformDataClassToSchema::class);

class PropertylessDataClassTransformActionTest extends Data {}

class DataClassTransformActionTest extends Data
{
    public function __construct(
        public string $property
    ) {}
}

it('creates a SchemaTree and sets its root to the Data class', function () {
    $schema = TransformDataClassToSchema::run(DataClassTransformActionTest::class);

    $reflection = new ReflectionClass($schema);

    $tree = $reflection->getProperty('tree')->getValue($schema);
    $treeReflection = new ReflectionClass($tree);
    $rootClass = $treeReflection->getProperty('rootClass')->getValue($tree);

    expect($tree)->toBeInstanceOf(SchemaTree::class);
    expect($rootClass)->toBe(DataClassTransformActionTest::class);
});

test('an existing SchemaTree can be passed to the action', function () {
    $tree = app(SchemaTree::class);

    $spy = $this->spy(SchemaTree::class);

    TransformDataClassToSchema::run(DataClassTransformActionTest::class, $tree);

    $spy->shouldNotHaveReceived('setRootClass');
});

it('increments the data class count on the SchemaTree', function () {
    $tree = app(SchemaTree::class);

    expect($tree->getDataClassCount(DataClassTransformActionTest::class))->toBe(0);

    TransformDataClassToSchema::run(DataClassTransformActionTest::class, $tree);

    expect($tree->getDataClassCount(DataClassTransformActionTest::class))->toBe(1);
});

it('registers the resulting Schema with the DataClassSchemaRegistry', function () {
    $registry = app(DataClassSchemaRegistry::class);

    expect($registry->has(DataClassTransformActionTest::class))->toBeFalse();

    TransformDataClassToSchema::run(DataClassTransformActionTest::class);

    expect($registry->has(DataClassTransformActionTest::class))->toBeTrue();
});

it('returns the existing Schema from the DataClassSchemaRegistry if it exists', function () {
    $registry = app(DataClassSchemaRegistry::class);

    $schema = ObjectSchema::make();

    $registry->register(DataClassTransformActionTest::class, $schema);

    expect(TransformDataClassToSchema::run(DataClassTransformActionTest::class))->toBe($schema);
});

it('creates an ObjectSchema from a Data class', function () {
    $schema = TransformDataClassToSchema::run(DataClassTransformActionTest::class);

    expect($schema)->toBeInstanceOf(ObjectSchema::class);
});

it('calls the ApplyAnnotationsToSchema action', function () {
    $mock = $this->mock(ApplyAnnotationsToSchema::class);

    $mock->shouldReceive('handle')->once()
        ->andReturn(ObjectSchema::make());

    $action = app(TransformDataClassToSchema::class);
    $action->handle(PropertylessDataClassTransformActionTest::class);
});

it('calls the ApplyRuleConfigurationsToSchema action', function () {
    $mock = $this->mock(ApplyRuleConfigurationsToSchema::class);

    $mock->shouldReceive('handle')->once()
        ->andReturn(ObjectSchema::make());

    $action = app(TransformDataClassToSchema::class);
    $action->handle(PropertylessDataClassTransformActionTest::class);
});

it('calls the ApplyPropertiesToDataObjectSchema action', function () {
    $mock = $this->mock(ApplyPropertiesToDataObjectSchema::class);

    $mock->shouldReceive('handle')->once()->andReturn(ObjectSchema::make());

    $action = app(TransformDataClassToSchema::class);
    $action->handle(DataClassTransformActionTest::class);
});

it('calls the ApplyRequiredToDataObjectSchema action', function () {
    $mock = $this->mock(ApplyRequiredToDataObjectSchema::class);

    $mock->shouldReceive('handle')->once()->andReturn(ObjectSchema::make());

    $action = app(TransformDataClassToSchema::class);
    $action->handle(DataClassTransformActionTest::class);
});
