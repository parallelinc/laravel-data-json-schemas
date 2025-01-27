<?php

use BasilLangevin\LaravelDataSchemas\Facades\JsonSchema;
use BasilLangevin\LaravelDataSchemas\Tests\Integration\DataClasses\HouseholdData;

it('can transform the HouseholdData class', function () {
    $output = JsonSchema::make(HouseholdData::class)->toArray();

    $expected = [
        '$schema' => 'https://json-schema.org/draft/2019-09/schema',
        'title' => 'Household',
        'type' => 'object',
        'properties' => [
            'id' => [
                'type' => 'string',
                'format' => 'uuid',
            ],
            'address' => [
                'type' => ['object', 'null'],
                'title' => 'Address',
                'properties' => [
                    'street' => [
                        'description' => 'The street name and number',
                        'type' => 'string',
                    ],
                    'apartment' => [
                        'type' => ['string', 'integer', 'null'],
                        'pattern' => '/^[a-zA-Z0-9_-]+$/',
                    ],
                    'city' => [
                        'type' => 'string',
                        'maxLength' => 100,
                    ],
                    'province' => [
                        'type' => 'string',
                        'enum' => ['AB', 'BC', 'MB', 'NB', 'NL', 'NS', 'ON', 'PE', 'QC', 'SK'],
                    ],
                    'postalCode' => [
                        'type' => 'string',
                        'pattern' => '^[A-Z]\d[A-Z] \d[A-Z]\d$',
                    ],
                ],
                'required' => ['street', 'city', 'province', 'postalCode'],
            ],
        ],
        'required' => ['id'],
    ];

    expect($output)->toEqual($expected);
});
