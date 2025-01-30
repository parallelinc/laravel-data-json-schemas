<?php

use BasilLangevin\LaravelDataSchemas\Actions\TransformDataClassToSchema;
use BasilLangevin\LaravelDataSchemas\Facades\JsonSchema;
use BasilLangevin\LaravelDataSchemas\Support\ClassWrapper;
use BasilLangevin\LaravelDataSchemas\Tests\Integration\DataClasses\AddressData;
use BasilLangevin\LaravelDataSchemas\Tests\Integration\DataClasses\HouseholdData;
use BasilLangevin\LaravelDataSchemas\Tests\Integration\DataClasses\PersonData;
use BasilLangevin\LaravelDataSchemas\Tests\Integration\DataClasses\PetData;
use BasilLangevin\LaravelDataSchemas\Tests\Integration\DataClasses\VacationData;
use BasilLangevin\LaravelDataSchemas\Tests\Integration\DataClasses\VehicleData;

it('can transform the HouseholdData class', function () {
    $output = JsonSchema::make(HouseholdData::class)->toArray();

    $makeSubschema = function (string $class) {
        return TransformDataClassToSchema::run(ClassWrapper::make($class))->toArray();
    };

    $expected = [
        '$schema' => 'https://json-schema.org/draft/2019-09/schema',
        'title' => 'Household',
        'type' => 'object',
        'properties' => [
            'id' => [
                'type' => ['string', 'integer'],
                'format' => 'uuid',
            ],
            'address' => [
                'description' => 'The family\'s home address.',
                'anyOf' => [
                    $makeSubschema(AddressData::class),
                    [
                        'type' => 'string',
                    ],
                    [
                        'type' => 'null',
                    ],
                ],
            ],
            'members' => [
                'type' => 'array',
                'items' => $makeSubschema(PersonData::class),
            ],
            'pets' => [
                'type' => 'array',
                'items' => $makeSubschema(PetData::class),
            ],
            'vacations' => [
                'type' => 'array',
                'items' => $makeSubschema(VacationData::class),
            ],
            'vehicles' => [
                'type' => ['array', 'null'],
                'items' => $makeSubschema(VehicleData::class),
            ],
            'favouriteNumbers' => [
                'type' => 'array',
                'minItems' => 3,
                'items' => [
                    'type' => 'integer',
                ],
            ],
        ],
        'required' => ['id', 'members', 'pets', 'vacations', 'favouriteNumbers'],
    ];

    expect($output)->toEqual($expected);
});
