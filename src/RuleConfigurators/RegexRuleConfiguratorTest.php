<?php

use BasilLangevin\LaravelDataJsonSchemas\RuleConfigurators\RegexRuleConfigurator;
use BasilLangevin\LaravelDataJsonSchemas\Tests\TestsSchemaTransformation;
use Spatie\LaravelData\Attributes\Validation\Regex;

covers(RegexRuleConfigurator::class);

uses(TestsSchemaTransformation::class);

it('applies the pattern keyword to a string schema')
    ->expect(fn () => $this->class->addStringProperty('test', [Regex::class => '/^[0-9]+$/']))
    ->toHaveSchema('test', [
        'type' => 'string',
        'pattern' => '/^[0-9]+$/',
    ]);
