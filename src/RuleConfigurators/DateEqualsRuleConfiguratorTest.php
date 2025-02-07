<?php

use BasilLangevin\LaravelDataJsonSchemas\RuleConfigurators\DateEqualsRuleConfigurator;
use BasilLangevin\LaravelDataJsonSchemas\Tests\Support\Argument;
use BasilLangevin\LaravelDataJsonSchemas\Tests\TestsSchemaTransformation;
use Spatie\LaravelData\Attributes\Validation\DateEquals;

covers(DateEqualsRuleConfigurator::class);

uses(TestsSchemaTransformation::class);

it('applies the format and const keywords to a string schema')
    ->expect(fn () => $this->class->addStringProperty('test', [DateEquals::class => new Argument("new Carbon\Carbon('2025-01-01')")]))
    ->toHaveSchema('test', [
        'type' => 'string',
        'format' => 'date-time',
        'const' => '2025-01-01T00:00:00Z',
    ]);
