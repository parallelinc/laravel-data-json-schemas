<?php

use BasilLangevin\LaravelDataSchemas\Actions\TransformDataClassToSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\ArraySchema;
use BasilLangevin\LaravelDataSchemas\Support\SchemaTree;
use BasilLangevin\LaravelDataSchemas\Tests\Integration\DataClasses\PersonData;

covers(ArraySchema::class);

it('returns its own schema when it is nested', function () {
    $tree = app(SchemaTree::class);

    $schema = ArraySchema::make()->applyType()->tree($tree)->minItems(1);

    expect($schema->toArray(true))->toEqual([
        'type' => 'array',
        'minItems' => 1,
    ]);
});

it('returns its own schema when it is not nested and the tree has no defs', function () {
    $tree = app(SchemaTree::class);

    $schema = ArraySchema::make()->applyType()->tree($tree)->minItems(1);

    expect($tree->hasDefs())->toBeFalse();

    expect($schema->toArray(false))->toEqual([
        'type' => 'array',
        'minItems' => 1,
    ]);
});

it('appends the defs to the schema when not nested and the tree has defs', function () {
    $tree = app(SchemaTree::class);

    $schema = ArraySchema::make()->tree($tree)
        ->items(TransformDataClassToSchema::run(PersonData::class, $tree));

    expect($tree->hasDefs())->toBeTrue();

    expect($schema->toArray(false))->toHaveKey('items', [
        '$ref' => '#/$defs/person',
    ]);
    expect($schema->toArray(false))->toHaveKey('$defs');
});
