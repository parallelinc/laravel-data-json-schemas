<?php

use BasilLangevin\LaravelDataSchemas\Actions\ApplyDateTimeFormatToSchema;
use BasilLangevin\LaravelDataSchemas\Enums\Format;
use BasilLangevin\LaravelDataSchemas\Schemas\StringSchema;
use BasilLangevin\LaravelDataSchemas\Tests\TestsSchemaTransformation;

covers(ApplyDateTimeFormatToSchema::class);

uses(TestsSchemaTransformation::class);

it('applies the date time format to the schema', function () {
    $schema = new StringSchema;

    $action = new ApplyDateTimeFormatToSchema;
    $result = $action->handle($schema);

    expect($result->getFormat())->toBe(Format::DateTime);
});
