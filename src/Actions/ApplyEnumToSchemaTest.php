<?php

use BasilLangevin\LaravelDataJsonSchemas\Actions\ApplyEnumToSchema;
use BasilLangevin\LaravelDataJsonSchemas\Exceptions\KeywordNotSetException;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\StringSchema;
use BasilLangevin\LaravelDataJsonSchemas\Tests\Support\Enums\TestStringEnum;
use BasilLangevin\LaravelDataJsonSchemas\Tests\TestsSchemaTransformation;

covers(ApplyEnumToSchema::class);

uses(TestsSchemaTransformation::class);

it('applies the enum to the schema', function () {
    $this->class->addProperty(TestStringEnum::class, 'property');
    $property = $this->class->getClassProperty('property');

    $schema = new StringSchema;

    $action = new ApplyEnumToSchema;
    $result = $action->handle($schema, $property);

    expect($result->getEnum())->toBe(TestStringEnum::class);
});

it('does not apply the enum to the schema if the enum does not exist', function () {
    $this->class->addProperty('NonExistentEnum', 'property');
    $property = $this->class->getClassProperty('property');

    $schema = new StringSchema;

    $action = new ApplyEnumToSchema;
    $result = $action->handle($schema, $property);

    expect(fn () => $result->getEnum())->toThrow(KeywordNotSetException::class);
});

it('does not apply the enum to the schema if the property is a union type', function () {
    $this->class->addProperty('string|int', 'property');
    $property = $this->class->getClassProperty('property');

    $schema = new StringSchema;

    $action = new ApplyEnumToSchema;
    $result = $action->handle($schema, $property);

    expect(fn () => $result->getEnum())->toThrow(KeywordNotSetException::class);
});
