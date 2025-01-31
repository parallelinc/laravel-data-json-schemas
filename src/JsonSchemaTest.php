<?php

use BasilLangevin\LaravelDataSchemas\Actions\TransformDataClassToSchema;
use BasilLangevin\LaravelDataSchemas\Enums\JsonSchemaDialect;
use BasilLangevin\LaravelDataSchemas\Exceptions\KeywordNotSetException;
use BasilLangevin\LaravelDataSchemas\JsonSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\ArraySchema;
use BasilLangevin\LaravelDataSchemas\Schemas\ObjectSchema;
use BasilLangevin\LaravelDataSchemas\Support\SchemaTree;
use BasilLangevin\LaravelDataSchemas\Tests\Integration\DataClasses\PersonData;
use Mockery\MockInterface;

covers(JsonSchema::class);

test('make runs the TransformDataClassToSchema action', function () {
    $this->mock(TransformDataClassToSchema::class, function (MockInterface $mock) {
        $mock->shouldReceive('handle')->once()->with(PersonData::class)->andReturn(ObjectSchema::make());
    });

    app(JsonSchema::class)->make(PersonData::class);
});

test('make adds the 2019-09 dialect to the schema by default', function () {
    $schema = app(JsonSchema::class)->make(PersonData::class);

    expect($schema->getDialect())->toBe(JsonSchemaDialect::Draft201909);
});

test('make adds whatever dialect is set in the config', function () {
    config(['data-schemas.dialect' => JsonSchemaDialect::Draft202012]);

    $schema = app(JsonSchema::class)->make(PersonData::class);

    expect($schema->getDialect())->toBe(JsonSchemaDialect::Draft202012);
});

test('make does not add a dialect if it is not set in the config', function () {
    config(['data-schemas.dialect' => null]);

    $schema = app(JsonSchema::class)->make(PersonData::class);

    expect(fn () => $schema->getDialect())
        ->toThrow(KeywordNotSetException::class, 'The keyword "dialect" has not been set.');
});

test('collect runs the TransformDataClassToSchema action with a tree', function () {
    $tree = $this->mock(SchemaTree::class);

    $this->mock(TransformDataClassToSchema::class, function (MockInterface $mock) use ($tree) {
        $mock->shouldReceive('handle')->once()->with(PersonData::class, $tree)->andReturn(ObjectSchema::make());
    });

    app(JsonSchema::class)->collect(PersonData::class);
});

test('collect returns an array schema with an ObjectSchema for the data class as its items', function () {
    $schema = app(JsonSchema::class)->collect(PersonData::class);

    expect($schema)->toBeInstanceOf(ArraySchema::class);

    $dataSchema = $schema->getItems();

    expect($dataSchema)->toBeInstanceOf(ObjectSchema::class);
    expect($dataSchema->toArray(true))->toEqual([
        '$ref' => '#/$defs/person',
    ]);
});

test('collect adds the 2019-09 dialect to the schema by default', function () {
    $schema = app(JsonSchema::class)->collect(PersonData::class);

    expect($schema->getDialect())->toBe(JsonSchemaDialect::Draft201909);
});

test('collect adds whatever dialect is set in the config', function () {
    config(['data-schemas.dialect' => JsonSchemaDialect::Draft202012]);

    $schema = app(JsonSchema::class)->collect(PersonData::class);

    expect($schema->getDialect())->toBe(JsonSchemaDialect::Draft202012);
});

test('collect does not add a dialect if it is not set in the config', function () {
    config(['data-schemas.dialect' => null]);

    $schema = app(JsonSchema::class)->collect(PersonData::class);

    expect(fn () => $schema->getDialect())
        ->toThrow(KeywordNotSetException::class, 'The keyword "dialect" has not been set.');
});

test('toArray transforms the data class into a JSON Schema array', function () {
    $result = app(JsonSchema::class)->toArray(PersonData::class);
    $expected = app(JsonSchema::class)->make(PersonData::class)->toArray();

    expect($result)->toEqual($expected);
});

test('collectToArray transforms the data class into a JSON Schema array', function () {
    $result = app(JsonSchema::class)->collectToArray(PersonData::class);
    $expected = app(JsonSchema::class)->collect(PersonData::class)->toArray();

    expect($result)->toEqual($expected);
});
