<?php

use BasilLangevin\LaravelDataSchemas\Facades\JsonSchema;
use BasilLangevin\LaravelDataSchemas\Tests\Integration\DataClasses\PersonData;

it('can transform the PersonData class', function () {
    $output = JsonSchema::toArray(PersonData::class);

    $expected = [
        '$schema' => 'https://json-schema.org/draft/2019-09/schema',
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
                    '$ref' => '#',
                ],
            ],
        ],
        'required' => [
            'firstName',
            'lastName',
            'age',
            'children',
        ],
    ];

    expect($output)->toEqual($expected);
});
