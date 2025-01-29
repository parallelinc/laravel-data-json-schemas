<?php

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Keywords\Array\ItemsKeyword;
use BasilLangevin\LaravelDataSchemas\Schemas\ArraySchema;
use BasilLangevin\LaravelDataSchemas\Schemas\StringSchema;

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
