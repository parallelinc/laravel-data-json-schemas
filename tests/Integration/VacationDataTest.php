<?php

use BasilLangevin\LaravelDataSchemas\Facades\JsonSchema;
use BasilLangevin\LaravelDataSchemas\Tests\Integration\DataClasses\VacationData;
use Carbon\Carbon;

it('can transform the VacationData class', function () {
    $output = JsonSchema::make(VacationData::class)->toArray();

    $expected = [
        '$schema' => 'https://json-schema.org/draft/2019-09/schema',
        'title' => 'Vacation',
        'type' => 'object',
        'properties' => [
            'destination' => [
                'type' => 'string',
            ],
            'startDate' => [
                'type' => 'string',
                'format' => 'date-time',
                'x-date-after-or-equal' => 'The value must be after or equal to '.Carbon::now()->startOfDay()->toIso8601ZuluString().'.',
            ],
            'endDate' => [
                'type' => ['string', 'null'],
                'format' => 'date-time',
            ],
        ],
        'required' => [
            'destination',
            'startDate',
        ],
    ];

    expect($output)->toEqual($expected);
});
