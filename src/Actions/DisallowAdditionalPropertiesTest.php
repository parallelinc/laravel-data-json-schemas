<?php

use BasilLangevin\LaravelDataJsonSchemas\Actions\DisallowAdditionalProperties;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\ObjectSchema;
use BasilLangevin\LaravelDataJsonSchemas\Tests\TestsSchemaTransformation;

covers(DisallowAdditionalProperties::class);

uses(TestsSchemaTransformation::class);

it('disallows additional properties', function () {
    $schema = new ObjectSchema;

    $action = new DisallowAdditionalProperties;
    $result = $action->handle($schema);

    expect($result->getAdditionalProperties())->toBeFalse();
});
