<?php

use BasilLangevin\LaravelDataJsonSchemas\Facades\JsonSchema;
use BasilLangevin\LaravelDataJsonSchemas\Tests\Integration\DataClasses\UnionArrayData;
use BasilLangevin\LaravelDataJsonSchemas\Tests\Integration\DataClasses\UnionArrayDataTypesData;

it('can transform union array annotation', function () {
    $output = JsonSchema::toArray(UnionArrayData::class);

    $expected = [
        '$schema' => 'https://json-schema.org/draft/2019-09/schema',
        'title' => 'Union Array',
        'type' => 'object',
        'properties' => [
            'type' => [
                'default' => 'text',
                'type' => 'string',
            ],
            'text' => [
                'type' => 'string',
            ],
            'marks' => [
                'type' => 'array',
                'items' => [
                    'anyOf' => [
                        [
                            'type' => 'string',
                        ],
                        [
                            'type' => 'integer',
                        ],
                    ],
                ],
            ],
        ],
        'required' => [
            'type',
            'text',
            'marks',
        ],
        'additionalProperties' => false,
    ];

    expect($expected)->toEqual($output);
});

it('can transform union array data types annotation', function () {
    $output = JsonSchema::toArray(UnionArrayDataTypesData::class);

    $expected = [
        '$schema' => 'https://json-schema.org/draft/2019-09/schema',
        'title' => 'Union Array Data Types',
        'type' => 'object',
        'properties' => [
            'type' => [
                'default' => 'text',
                'type' => 'string',
            ],
            'text' => [
                'type' => 'string',
            ],
            'marks' => [
                'type' => 'array',
                'items' => [
                    'anyOf' => [
                        [
                            'title' => 'Address',
                            'type' => 'object',
                            'properties' => [
                                'street' => [
                                    'description' => 'The street name and number',
                                    'type' => 'string',
                                ],
                                'apartment' => [
                                    'type' => [
                                        'string',
                                        'integer',
                                        'null',
                                    ],
                                    'pattern' => '/^[a-zA-Z0-9_-]+$/',
                                ],
                                'city' => [
                                    'type' => 'string',
                                    'maxLength' => 100,
                                ],
                                'province' => [
                                    'type' => 'string',
                                    'enum' => [
                                        'AB',
                                        'BC',
                                        'MB',
                                        'NB',
                                        'NL',
                                        'NS',
                                        'ON',
                                        'PE',
                                        'QC',
                                        'SK',
                                    ],
                                ],
                                'postalCode' => [
                                    'type' => 'string',
                                    'pattern' => '^[A-Z]\\d[A-Z] \\d[A-Z]\\d$',
                                ],
                            ],
                            'required' => [
                                'street',
                                'city',
                                'province',
                                'postalCode',
                            ],
                            'additionalProperties' => false,
                        ],
                        [
                            'title' => 'Pet',
                            'type' => 'object',
                            'properties' => [
                                'name' => [
                                    'type' => 'string',
                                ],
                                'species' => [
                                    'type' => 'string',
                                    'enum' => [
                                        'dog',
                                        'cat',
                                        'bird',
                                        'fish',
                                        'reptile',
                                        'other',
                                    ],
                                ],
                                'breed' => [
                                    'type' => [
                                        'string',
                                        'null',
                                    ],
                                ],
                                'age' => [
                                    'description' => 'The age of the pet in years',
                                    'type' => 'integer',
                                    'maximum' => 100,
                                ],
                            ],
                            'required' => [
                                'name',
                                'species',
                                'age',
                            ],
                            'additionalProperties' => false,
                        ],
                    ],
                ],
            ],
        ],
        'required' => [
            'type',
            'text',
            'marks',
        ],
        'additionalProperties' => false,
    ];

    expect($expected)->toEqual($output);
});
