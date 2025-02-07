<?php

use BasilLangevin\LaravelDataJsonSchemas\RuleConfigurators\UuidRuleConfigurator;
use BasilLangevin\LaravelDataJsonSchemas\Tests\TestsSchemaTransformation;
use Spatie\LaravelData\Attributes\Validation\Uuid;

covers(UuidRuleConfigurator::class);

uses(TestsSchemaTransformation::class);

it('applies the format keyword to a string schema')
    ->expect(fn () => $this->class->addStringProperty('test', [Uuid::class]))
    ->toHaveSchema('test', [
        'type' => 'string',
        'format' => 'uuid',
    ]);
