<?php

use BasilLangevin\LaravelDataSchemas\Facades\JsonSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\ObjectSchema;
use Illuminate\Support\Arr;
use Spatie\LaravelData\Data;

$basicSchema = [
    '$schema' => 'https://json-schema.org/draft/2019-09/schema',
    'type' => 'object',
    'properties' => [
        'testParameter' => [
            'type' => 'boolean',
        ],
    ],
    'required' => [
        'testParameter',
    ],
];

it('can create a basic boolean schema from a data object', function () use ($basicSchema) {
    class BasicBooleanData extends Data
    {
        public function __construct(
            public bool $testParameter,
        ) {}
    }

    $schema = JsonSchema::make(BasicBooleanData::class);

    expect($schema)->toBeInstanceOf(ObjectSchema::class);

    expect($schema->toArray())->toEqual(
        array_merge(
            $basicSchema,
            ['title' => 'Basic Boolean'],
        ),
    );
});

it('can create a boolean schema with a description from a data object', function () use ($basicSchema) {
    class BooleanWithDescriptionData extends Data
    {
        public function __construct(
            /** The test parameter. */
            public bool $testParameter,
        ) {}
    }

    $schema = JsonSchema::make(BooleanWithDescriptionData::class);

    expect($schema)->toBeInstanceOf(ObjectSchema::class);

    Arr::set($basicSchema, 'properties.testParameter.description', 'The test parameter.');

    expect($schema->toArray())->toEqual(
        array_merge(
            $basicSchema,
            ['title' => 'Boolean With Description'],
        ),
    );
});
