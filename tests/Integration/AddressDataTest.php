<?php

use BasilLangevin\LaravelDataJsonSchemas\Facades\JsonSchema;
use BasilLangevin\LaravelDataJsonSchemas\Tests\Integration\DataClasses\AddressData;

it('can transform the AddressData class', function () {
    $output = JsonSchema::toArray(AddressData::class);

    $expected = [
        '$schema' => 'https://json-schema.org/draft/2019-09/schema',
        'title' => 'Address',
        'type' => 'object',
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
                'enum' => [
                    'AB', 'BC', 'MB', 'NB', 'NL', 'NS', 'ON', 'PE', 'QC', 'SK',
                ],
            ],
            'postalCode' => [
                'type' => 'string',
                'pattern' => "^[A-Z]\d[A-Z] \d[A-Z]\d$",
            ],
        ],
        'required' => [
            'street', 'city', 'province', 'postalCode',
        ],
        'additionalProperties' => false,
    ];

    expect($output)->toEqual($expected);
});
