<?php

use BasilLangevin\LaravelDataJsonSchemas\Facades\JsonSchema;
use BasilLangevin\LaravelDataJsonSchemas\Tests\Integration\DataClasses\PetData;

it('can transform the PetData class', function () {
    $output = JsonSchema::toArray(PetData::class);

    $expected = [
        '$schema' => 'https://json-schema.org/draft/2019-09/schema',
        'title' => 'Pet',
        'type' => 'object',
        'properties' => [
            'name' => [
                'type' => 'string',
            ],
            'species' => [
                'type' => 'string',
                'enum' => ['dog', 'cat', 'bird', 'fish', 'reptile', 'other'],
            ],
            'breed' => [
                'type' => ['string', 'null'],
            ],
            'age' => [
                'description' => 'The age of the pet in years',
                'type' => 'integer',
                'maximum' => 100,
            ],
        ],
        'required' => ['name', 'species', 'age'],
        'additionalProperties' => false,
    ];

    expect($output)->toEqual($expected);
});
