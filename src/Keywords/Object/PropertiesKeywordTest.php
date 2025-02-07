<?php

use BasilLangevin\LaravelDataJsonSchemas\Enums\DataType;
use BasilLangevin\LaravelDataJsonSchemas\Keywords\General\TypeKeyword;
use BasilLangevin\LaravelDataJsonSchemas\Keywords\Object\PropertiesKeyword;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\BooleanSchema;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\NumberSchema;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\ObjectSchema;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\StringSchema;

covers(PropertiesKeyword::class);

class PropertiesKeywordTestSchema extends ObjectSchema
{
    public static array $keywords = [
        TypeKeyword::class,
        PropertiesKeyword::class,
    ];
}

$properties = [
    'test' => BooleanSchema::make(),
    'test2' => BooleanSchema::make(),
];

$additionalProperties = [
    'test2' => NumberSchema::make(),
    'test3' => BooleanSchema::make(),
];

$basicOutput = collect([
    'type' => DataType::Object->value,
]);

it('can set its properties')
    ->expect(PropertiesKeywordTestSchema::make())
    ->properties($properties)
    ->getProperties()->toBe($properties);

it('can apply the properties to a schema')
    ->expect(PropertiesKeywordTestSchema::make())
    ->properties($properties)
    ->applyKeyword(PropertiesKeyword::class, $basicOutput)
    ->toEqual(collect([
        'type' => DataType::Object->value,
        'properties' => [
            'test' => $properties['test']->toArray(),
            'test2' => $properties['test2']->toArray(),
        ],
    ]));

it('passes the nested flag when applying the properties to a schema', function () use ($basicOutput) {
    $spy = $this->spy(StringSchema::class);

    $properties = [
        'test' => app(StringSchema::class),
    ];

    $schema = ObjectSchema::make()->properties($properties);

    $schema->applyKeyword(PropertiesKeyword::class, $basicOutput);

    $spy->shouldHaveReceived('toArray')
        ->with(true)
        ->once();
});

it('combines the properties of each instance when multiple instances are set')
    ->expect(PropertiesKeywordTestSchema::make()
        ->properties($properties)
        ->properties($additionalProperties)
    )
    ->applyKeyword(PropertiesKeyword::class, $basicOutput)
    ->toEqual(collect([
        'type' => DataType::Object->value,
        'properties' => [
            'test' => $properties['test']->toArray(),
            'test2' => $additionalProperties['test2']->toArray(),
            'test3' => $additionalProperties['test3']->toArray(),
        ],
    ]));
