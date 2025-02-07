<?php

use BasilLangevin\LaravelDataJsonSchemas\RuleConfigurators\AlphaNumericRuleConfigurator;
use BasilLangevin\LaravelDataJsonSchemas\Tests\TestsSchemaTransformation;
use Spatie\LaravelData\Attributes\Validation\AlphaNumeric;

covers(AlphaNumericRuleConfigurator::class);

uses(TestsSchemaTransformation::class);

it('applies the pattern keyword to a string schema')
    ->expect(fn () => $this->class->addStringProperty('test', [AlphaNumeric::class]))
    ->toHaveSchema('test', [
        'type' => 'string',
        'pattern' => '/^[a-zA-Z0-9]+$/',
    ]);
