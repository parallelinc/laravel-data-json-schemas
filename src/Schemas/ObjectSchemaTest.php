<?php

use BasilLangevin\LaravelDataJsonSchemas\Actions\TransformDataClassToSchema;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\ArraySchema;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\ObjectSchema;
use BasilLangevin\LaravelDataJsonSchemas\Support\SchemaTree;
use BasilLangevin\LaravelDataJsonSchemas\Tests\Integration\DataClasses\PersonData;
use BasilLangevin\LaravelDataJsonSchemas\Tests\Integration\DataClasses\VehicleData;

covers(ObjectSchema::class);

$vehicleSchema = [
    'title' => 'Vehicle',
    'type' => 'object',
    'properties' => [
        'make' => [
            'type' => 'string',
        ],
        'model' => [
            'type' => 'string',
        ],
        'year' => [
            'type' => 'integer',
        ],
        'vin' => [
            'type' => 'string',
        ],
    ],
    'required' => [
        'make',
        'model',
        'year',
        'vin',
    ],
];

it('returns its own schema when it is nested and it does not have a class', function () {
    $schema = ObjectSchema::make()->applyType()->minProperties(3);

    $reflection = new ReflectionObject($schema);

    expect($reflection->getProperty('class')->isInitialized($schema))->toBeFalse();

    expect($schema->toArray(true))->toEqual([
        'type' => 'object',
        'minProperties' => 3,
    ]);
});

it('returns a ref when it is nested and has been defined multiple times', function () {
    $tree = app(SchemaTree::class);

    $schema = ArraySchema::make()->tree($tree)
        ->items(TransformDataClassToSchema::run(PersonData::class, $tree));

    $objectSchema = $schema->getItems();

    $reflection = new ReflectionObject($objectSchema);
    expect($reflection->getProperty('class')->isInitialized($objectSchema))->toBeTrue();

    expect($objectSchema->toArray(true))->toEqual([
        '$ref' => '#/$defs/person',
    ]);
});

it('returns its own schema when it is nested and has not been defined multiple times', function () use ($vehicleSchema) {
    $tree = app(SchemaTree::class);

    $schema = ArraySchema::make()->tree($tree)
        ->items(TransformDataClassToSchema::run(VehicleData::class, $tree));

    $objectSchema = $schema->getItems();

    expect($tree->hasDefs())->toBeFalse();

    expect($objectSchema->toArray(true))->toEqual($vehicleSchema);
});

it('returns its own schema when it is not nested and the tree has no defs', function () use ($vehicleSchema) {
    $tree = app(SchemaTree::class)->rootClass(VehicleData::class);

    $schema = TransformDataClassToSchema::run(VehicleData::class, $tree);

    expect($tree->hasDefs())->toBeFalse();

    expect($schema->toArray(false))->toEqual($vehicleSchema);
});

it('appends the defs to the schema when not nested and the tree has defs', function () {
    $tree = app(SchemaTree::class);

    $schema = ArraySchema::make()->tree($tree)
        ->items(TransformDataClassToSchema::run(PersonData::class, $tree));

    expect($tree->hasDefs())->toBeTrue();

    expect($schema->toArray(false))->toHaveKey('$defs');
});
