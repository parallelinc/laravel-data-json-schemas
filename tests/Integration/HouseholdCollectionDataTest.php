<?php

use BasilLangevin\LaravelDataSchemas\Actions\TransformDataClassToSchema;
use BasilLangevin\LaravelDataSchemas\Facades\JsonSchema;
use BasilLangevin\LaravelDataSchemas\Support\SchemaTree;
use BasilLangevin\LaravelDataSchemas\Tests\Integration\DataClasses\AddressData;
use BasilLangevin\LaravelDataSchemas\Tests\Integration\DataClasses\HouseholdData;
use BasilLangevin\LaravelDataSchemas\Tests\Integration\DataClasses\PersonData;
use BasilLangevin\LaravelDataSchemas\Tests\Integration\DataClasses\PetData;
use BasilLangevin\LaravelDataSchemas\Tests\Integration\DataClasses\VacationData;
use BasilLangevin\LaravelDataSchemas\Tests\Integration\DataClasses\VehicleData;

it('can transform the HouseholdData class', function () {
    $output = JsonSchema::collectToArray(HouseholdData::class);

    $makeSubschema = function (string $class) {
        return TransformDataClassToSchema::run($class, app(SchemaTree::class))->buildSchema();
    };

    $expected = [
        '$schema' => 'https://json-schema.org/draft/2019-09/schema',
        'items' => [
            'title' => 'Household',
            'type' => 'object',
            'properties' => [
                'id' => [
                    'type' => ['string', 'integer'],
                    'format' => 'uuid',
                ],
                'home_address' => [
                    'description' => 'The family\'s home address.',
                    'anyOf' => [
                        ['$ref' => '#/$defs/address'],
                        ['type' => 'string'],
                        ['type' => 'null'],
                    ],
                ],
                'vacation_address' => [
                    'description' => 'The family\'s vacation address.',
                    'anyOf' => [
                        ['$ref' => '#/$defs/address'],
                        ['type' => 'string'],
                        ['type' => 'null'],
                    ],
                ],
                'members' => [
                    'type' => 'array',
                    'items' => ['$ref' => '#/$defs/person'],
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
        ],
        '$defs' => [
            'address' => $makeSubschema(AddressData::class),
            'person' => $makeSubschema(PersonData::class),
        ],
    ];

    expect($output)->toEqual($expected);
});
