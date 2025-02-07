<?php

use BasilLangevin\LaravelDataJsonSchemas\RuleConfigurators\ActiveUrlRuleConfigurator;
use BasilLangevin\LaravelDataJsonSchemas\Tests\TestsSchemaTransformation;
use Spatie\LaravelData\Attributes\Validation\ActiveUrl;

covers(ActiveUrlRuleConfigurator::class);

uses(TestsSchemaTransformation::class);

it('applies the format keyword to a string schema')
    ->expect(fn () => $this->class->addStringProperty('test', [ActiveUrl::class]))
    ->toHaveSchema('test', [
        'type' => 'string',
        'format' => 'uri',
    ]);
