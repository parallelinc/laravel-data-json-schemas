<?php

use BasilLangevin\LaravelDataSchemas\Schemas\ObjectSchema;
use BasilLangevin\LaravelDataSchemas\Support\SchemaTree;

covers(SchemaTree::class);

it('can register a schema', function () {
    $tree = app(SchemaTree::class);

    $schema = ObjectSchema::make();

    $tree->registerSchema('App\Data\UserData', $schema);
    expect($tree->hasRegisteredSchema('App\Data\UserData'))->toBeTrue();
    expect($tree->getRegisteredSchema('App\Data\UserData'))->toBe($schema);
});

it('can check if it has a registered schema', function () {
    $tree = app(SchemaTree::class);

    expect($tree->hasRegisteredSchema('App\Data\UserData'))->toBeFalse();

    $tree->registerSchema('App\Data\UserData', ObjectSchema::make());
    expect($tree->hasRegisteredSchema('App\Data\UserData'))->toBeTrue();
});

it('can increment the count of a data class', function () {
    $tree = app(SchemaTree::class);

    $tree->incrementDataClassCount('App\Data\UserData');
    expect($tree->getDataClassCount('App\Data\UserData'))->toBe(1);

    $tree->incrementDataClassCount('App\Data\UserData');
    expect($tree->getDataClassCount('App\Data\UserData'))->toBe(2);
});

it('can get the count of a data class', function () {
    $tree = app(SchemaTree::class);

    expect($tree->getDataClassCount('App\Data\UserData'))->toBe(0);

    $tree->incrementDataClassCount('App\Data\UserData');
    expect($tree->getDataClassCount('App\Data\UserData'))->toBe(1);

    $tree->incrementDataClassCount('App\Data\UserData');
    expect($tree->getDataClassCount('App\Data\UserData'))->toBe(2);
});

it('can check if a data class has multiple occurrences', function () {
    $tree = app(SchemaTree::class);

    $tree->incrementDataClassCount('App\Data\UserData');
    expect($tree->hasMultiple('App\Data\UserData'))->toBeFalse();

    $tree->incrementDataClassCount('App\Data\UserData');
    expect($tree->hasMultiple('App\Data\UserData'))->toBeTrue();
});

it('can get the ref names of the data classes', function () {
    $tree = app(SchemaTree::class);

    $tree->incrementDataClassCount('App\Data\UserDto');
    expect($tree->getRefNames())->toEqual(['App\Data\UserDto' => '#/$defs/user-dto']);
});

it('removes the Data suffix from the ref name', function () {
    $tree = app(SchemaTree::class);

    $tree->incrementDataClassCount('App\Data\UserData');
    expect($tree->getRefNames())->toEqual(['App\Data\UserData' => '#/$defs/user']);
});

it('replaces the root ref name with a #', function () {
    $tree = app(SchemaTree::class);

    $tree->rootClass('App\Data\UserData');
    $tree->incrementDataClassCount('App\Data\UserData');
    expect($tree->getRefNames())->toEqual(['App\Data\UserData' => '#']);
});

it('numbers duplicate ref names', function () {
    $tree = app(SchemaTree::class);

    $tree->incrementDataClassCount('App\Data\UserData');
    $tree->incrementDataClassCount('App\Data\PetData');
    $tree->incrementDataClassCount('App\SecondaryData\UserData');
    expect($tree->getRefNames())->toEqual([
        'App\Data\UserData' => '#/$defs/user-1',
        'App\Data\PetData' => '#/$defs/pet',
        'App\SecondaryData\UserData' => '#/$defs/user-2',
    ]);
});

it('can get the ref name of a data class', function () {
    $tree = app(SchemaTree::class);

    $tree->incrementDataClassCount('App\Data\UserData');
    expect($tree->getRefName('App\Data\UserData'))->toBe('#/$defs/user');
});

it('can get the ref name when multiple data classes have the same name', function () {
    $tree = app(SchemaTree::class);

    $tree->incrementDataClassCount('App\Data\UserData');
    $tree->incrementDataClassCount('App\SecondaryData\UserData');
    expect($tree->getRefName('App\Data\UserData'))->toBe('#/$defs/user-1');
    expect($tree->getRefName('App\SecondaryData\UserData'))->toBe('#/$defs/user-2');
});
