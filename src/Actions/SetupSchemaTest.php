<?php

use BasilLangevin\LaravelDataSchemas\Actions\SetupSchema;
use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Schemas\IntegerSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\StringSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\UnionSchema;
use BasilLangevin\LaravelDataSchemas\Tests\TestsSchemaTransformation;

covers(SetupSchema::class);

uses(TestsSchemaTransformation::class);

it('can apply the type to the schema', function () {
    $schema = new StringSchema;

    $this->class->addStringProperty('test');
    $property = $this->class->getClassProperty('test');

    SetupSchema::run($schema, $property, $this->tree);

    expect($schema->getType())->toBe(DataType::String);
});

it('can apply the type to a union schema', function () {
    $schema = new UnionSchema;

    $this->class->addProperty('string|int', 'test');
    $property = $this->class->getClassProperty('test');

    SetupSchema::run($schema, $property, $this->tree);

    expect($schema->getConstituentSchemas())->toHaveCount(2);
    expect($schema->getConstituentSchemas()->first())->toBeInstanceOf(StringSchema::class);
    expect($schema->getConstituentSchemas()->last())->toBeInstanceOf(IntegerSchema::class);
});
