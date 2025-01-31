<?php

use BasilLangevin\LaravelDataSchemas\Facades\JsonSchema;
use BasilLangevin\LaravelDataSchemas\Tests\Integration\DataClasses\VehicleData;

it('can transform the VehicleData class', function () {
    $output = JsonSchema::toArray(VehicleData::class);

    $expected = [
        '$schema' => 'https://json-schema.org/draft/2019-09/schema',
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
    ];

    expect($output)->toEqual($expected);
});
