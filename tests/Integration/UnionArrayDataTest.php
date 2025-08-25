<?php

use BasilLangevin\LaravelDataJsonSchemas\Facades\JsonSchema;
use BasilLangevin\LaravelDataJsonSchemas\Tests\Integration\DataClasses\UnionArrayData;

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
