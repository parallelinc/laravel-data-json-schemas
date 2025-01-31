<?php

use BasilLangevin\LaravelDataSchemas\Facades\JsonSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\ObjectSchema;
use Illuminate\Support\Arr;
use Spatie\LaravelData\Data;

enum PrimativesTestStringEnum: string
{
    case First = 'first';
    case Second = 'second';
}

$basicStringEnumSchema = [
    '$schema' => 'https://json-schema.org/draft/2019-09/schema',
    'type' => 'object',
    'properties' => [
        'testParameter' => [
            'type' => 'string',
            'enum' => [
                'first',
                'second',
            ],
        ],
    ],
    'required' => [
        'testParameter',
    ],
];

it('can create a basic string enum schema from a data object', function () use ($basicStringEnumSchema) {
    class BasicStringEnumData extends Data
    {
        public function __construct(
            public PrimativesTestStringEnum $testParameter,
        ) {}
    }

    $schema = JsonSchema::make(BasicStringEnumData::class);

    expect($schema)->toBeInstanceOf(ObjectSchema::class);

    expect($schema->toArray())->toEqual(
        array_merge(
            $basicStringEnumSchema,
            ['title' => 'Basic String Enum'],
        ),
    );
});

it('can create a string enum schema with a description from a data object', function () use ($basicStringEnumSchema) {
    class StringEnumWithDescriptionData extends Data
    {
        public function __construct(
            /** The test parameter. */
            public PrimativesTestStringEnum $testParameter,
        ) {}
    }

    $schema = JsonSchema::make(StringEnumWithDescriptionData::class);

    expect($schema)->toBeInstanceOf(ObjectSchema::class);

    Arr::set($basicStringEnumSchema, 'properties.testParameter.description', 'The test parameter.');

    expect($schema->toArray())->toEqual(
        array_merge(
            $basicStringEnumSchema,
            ['title' => 'String Enum With Description'],
        ),
    );
});

enum PrimativesTestIntegerEnum: int
{
    case First = 1;
    case Second = 2;
}

$basicIntegerEnumSchema = [
    '$schema' => 'https://json-schema.org/draft/2019-09/schema',
    'type' => 'object',
    'properties' => [
        'testParameter' => [
            'type' => 'integer',
            'enum' => [
                1,
                2,
            ],
            'x-enum-values' => [
                'First' => 1,
                'Second' => 2,
            ],
        ],
    ],
    'required' => [
        'testParameter',
    ],
];

it('can create a basic integer enum schema from a data object', function () use ($basicIntegerEnumSchema) {
    class BasicIntegerEnumData extends Data
    {
        public function __construct(
            public PrimativesTestIntegerEnum $testParameter,
        ) {}
    }

    $schema = JsonSchema::make(BasicIntegerEnumData::class);

    expect($schema)->toBeInstanceOf(ObjectSchema::class);

    expect($schema->toArray())->toEqual(
        array_merge(
            $basicIntegerEnumSchema,
            ['title' => 'Basic Integer Enum'],
        ),
    );
});

it('can create an integer enum schema with a description from a data object', function () use ($basicIntegerEnumSchema) {
    class IntegerEnumWithDescriptionData extends Data
    {
        public function __construct(
            /** The test parameter. */
            public PrimativesTestIntegerEnum $testParameter,
        ) {}
    }

    $schema = JsonSchema::make(IntegerEnumWithDescriptionData::class);

    expect($schema)->toBeInstanceOf(ObjectSchema::class);

    Arr::set($basicIntegerEnumSchema, 'properties.testParameter.description', 'The test parameter.');

    expect($schema->toArray())->toEqual(
        array_merge(
            $basicIntegerEnumSchema,
            ['title' => 'Integer Enum With Description'],
        ),
    );
});
