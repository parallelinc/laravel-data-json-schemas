<?php

use BasilLangevin\LaravelDataJsonSchemas\Actions\ApplyDateTimeFormatToSchema;
use BasilLangevin\LaravelDataJsonSchemas\Enums\Format;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\StringSchema;
use BasilLangevin\LaravelDataJsonSchemas\Tests\TestsSchemaTransformation;

covers(ApplyDateTimeFormatToSchema::class);

uses(TestsSchemaTransformation::class);

it('applies the date time format to the schema', function () {
    $schema = new StringSchema;

    $action = new ApplyDateTimeFormatToSchema;
    $result = $action->handle($schema);

    expect($result->getFormat())->toBe(Format::DateTime);
});
