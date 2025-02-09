<?php

use BasilLangevin\LaravelDataJsonSchemas\Facades\JsonSchema;
use BasilLangevin\LaravelDataJsonSchemas\Tests\Integration\DataClasses\VehicleData;

it('can transform the VehicleData class', function () {
    $output = JsonSchema::collectToArray(VehicleData::class);

    $expected = [
        '$schema' => 'https://json-schema.org/draft/2019-09/schema',
        'items' => [
            'title' => 'Vehicle',
            'type' => 'object',
            'properties' => [
                'make' => [
                    'type' => 'string',
                ],
                'model' => [
                    'type' => 'string',
                ],
                'year' => [
                    'type' => 'integer',
                ],
                'vin' => [
                    'type' => 'string',
                ],
            ],
            'required' => [
                'make',
                'model',
                'year',
                'vin',
            ],
            'additionalProperties' => false,
        ],
    ];

    expect($output)->toEqual($expected);
});
