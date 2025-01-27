<?php

use BasilLangevin\LaravelDataSchemas\Facades\JsonSchema;
use BasilLangevin\LaravelDataSchemas\Tests\Integration\DataClasses\PersonData;

it('can transform the PersonData class', function () {
    $output = JsonSchema::make(PersonData::class)->toArray();

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
        ],
        'required' => [
            'firstName',
            'lastName',
            'age',
        ],
    ];

    expect($output)->toEqual($expected);
});
