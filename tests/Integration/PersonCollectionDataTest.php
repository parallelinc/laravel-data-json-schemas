<?php

use BasilLangevin\LaravelDataJsonSchemas\Facades\JsonSchema;
use BasilLangevin\LaravelDataJsonSchemas\Tests\Integration\DataClasses\PersonData;

it('can transform the PersonData class', function () {
    $output = JsonSchema::collectToArray(PersonData::class);

    $expected = [
        '$schema' => 'https://json-schema.org/draft/2019-09/schema',
        'items' => [
            '$ref' => '#/$defs/person',
        ],
        '$defs' => [
            'person' => [
                'title' => 'Person',
                'type' => 'object',
                'properties' => [
                    'firstName' => [
                        'type' => 'string',
                    ],
                    'lastName' => [
                        'type' => 'string',
                    ],
                    'middleName' => [
                        'type' => ['string', 'null'],
                    ],
                    'age' => [
                        'type' => 'integer',
                        'maximum' => 100,
                    ],
                    'children' => [
                        'type' => 'array',
                        'items' => [
                            '$ref' => '#/$defs/person',
                        ],
                    ],
                ],
                'required' => [
                    'firstName',
                    'lastName',
                    'age',
                    'children',
                ],
                'additionalProperties' => false,
            ],
        ],
    ];

    expect($output)->toEqual($expected);
});
