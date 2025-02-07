<?php

use BasilLangevin\LaravelDataJsonSchemas\Enums\DataType;
use BasilLangevin\LaravelDataJsonSchemas\Keywords\Array\ItemsKeyword;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\ArraySchema;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\ObjectSchema;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\StringSchema;

covers(ItemsKeyword::class);

$basicOutput = collect([
    'type' => DataType::Array->value,
]);

$stringSchema = StringSchema::make();

it('can set its items')
    ->expect(fn () => ArraySchema::make()->items($stringSchema))
    ->getItems()->toBe($stringSchema);

it('can get its items')
    ->expect(ArraySchema::make()->items($stringSchema))
    ->getItems()->toBe($stringSchema);

it('can apply the items to a schema')
    ->expect(ArraySchema::make()->items($stringSchema))
    ->applyKeyword(ItemsKeyword::class, $basicOutput)
    ->toEqual(collect([
        'type' => DataType::Array->value,
        'items' => $stringSchema->toArray(),
    ]));

it('passes the nested flag when applying the items to a schema', function () use ($basicOutput) {
    $spy = $this->spy(ObjectSchema::class);

    $subSchema = app(ObjectSchema::class);

    $schema = ArraySchema::make()->items($subSchema);

    $schema->applyKeyword(ItemsKeyword::class, $basicOutput);

    $spy->shouldHaveReceived('toArray')
        ->with(true)
        ->once();
});

it('wraps multiple instances in an anyOf')
    ->expect(ArraySchema::make()->items($stringSchema)->items($stringSchema))
    ->applyKeyword(ItemsKeyword::class, $basicOutput)
    ->toEqual(collect([
        'type' => DataType::Array->value,
        'items' => [
            'anyOf' => [
                $stringSchema->toArray(),
                $stringSchema->toArray(),
            ],
        ],
    ]));

it('passes the nested flag when applying multiple instances to a schema', function () use ($basicOutput) {
    $spy = $this->spy(ObjectSchema::class);

    $subSchema1 = app(ObjectSchema::class);
    $subSchema2 = app(ObjectSchema::class);

    $schema = ArraySchema::make()->items($subSchema1)->items($subSchema2);

    $schema->applyKeyword(ItemsKeyword::class, $basicOutput);

    $spy->shouldHaveReceived('toArray')
        ->with(true)
        ->twice();
});
