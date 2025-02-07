<?php

use BasilLangevin\LaravelDataJsonSchemas\Actions\ApplyRuleConfigurationsToSchema;
use BasilLangevin\LaravelDataJsonSchemas\Actions\MakeSchemaForReflectionType;
use BasilLangevin\LaravelDataJsonSchemas\Attributes\Title;
use BasilLangevin\LaravelDataJsonSchemas\Exceptions\KeywordNotSetException;
use BasilLangevin\LaravelDataJsonSchemas\RuleConfigurators\Contracts\ConfiguresAnySchema;
use BasilLangevin\LaravelDataJsonSchemas\RuleConfigurators\Contracts\ConfiguresArraySchema;
use BasilLangevin\LaravelDataJsonSchemas\RuleConfigurators\Contracts\ConfiguresBooleanSchema;
use BasilLangevin\LaravelDataJsonSchemas\RuleConfigurators\Contracts\ConfiguresIntegerSchema;
use BasilLangevin\LaravelDataJsonSchemas\RuleConfigurators\Contracts\ConfiguresNumberSchema;
use BasilLangevin\LaravelDataJsonSchemas\RuleConfigurators\Contracts\ConfiguresObjectSchema;
use BasilLangevin\LaravelDataJsonSchemas\RuleConfigurators\Contracts\ConfiguresStringSchema;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\ObjectSchema;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\StringSchema;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\UnionSchema;
use BasilLangevin\LaravelDataJsonSchemas\Tests\TestsSchemaTransformation;
use Spatie\LaravelData\Attributes\Validation\Alpha;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Same;
use Spatie\LaravelData\Attributes\Validation\ValidationAttribute;

covers(ApplyRuleConfigurationsToSchema::class);

uses(TestsSchemaTransformation::class);

it('applies the correct rule configurations to the schema', function () {
    $this->class->addStringProperty('name', [Alpha::class, Title::class => 'title']);

    $property = $this->class->getClassProperty('name');

    $action = new ApplyRuleConfigurationsToSchema;

    $schema = new StringSchema;

    $result = $action->handle($schema, $property);

    expect($result->getPattern())->toBe('/^[a-zA-Z]+$/');

    expect(fn () => $result->getTitle())->toThrow(Exception::class, 'The keyword "title" has not been set.');
});

it('applies the correct rule configurations to a union schema', function () {
    $this->class->addProperty('string|int', 'name', [Alpha::class, Title::class => 'title', Min::class => 1]);

    $property = $this->class->getClassProperty('name');

    $action = new ApplyRuleConfigurationsToSchema;

    $schema = (new UnionSchema)->buildConstituentSchemas($property, $this->tree);

    $result = $action->handle($schema, $property);

    $constituents = $result->getConstituentSchemas();

    $stringSchema = $constituents->first();
    $integerSchema = $constituents->last();

    expect($stringSchema->getPattern())->toBe('/^[a-zA-Z]+$/');
    expect($stringSchema->getMinLength())->toBe(1);

    expect($integerSchema->getMinimum())->toBe(1);
});

it('does not apply any rule configurations to the union schema itself', function () {
    $this->class->addProperty('string|int', 'name', [Same::class => 'otherProperty']);
    $this->class->addStringProperty('otherProperty');

    $property = $this->class->getClassProperty('name');

    $action = new ApplyRuleConfigurationsToSchema;

    $schema = (new UnionSchema)->buildConstituentSchemas($property, $this->tree);

    $result = $action->handle($schema, $property);

    expect(fn () => $result->getCustomAnnotation())->toThrow(KeywordNotSetException::class, 'The keyword "customAnnotation" has not been set.');
});

test('getConfigurableAttributes only includes validation attributes', function () {
    $this->class->addStringProperty('name', [Alpha::class, Title::class => 'title']);
    $property = $this->class->getClassProperty('name');

    $action = new ApplyRuleConfigurationsToSchema;

    $reflection = new ReflectionClass($action);
    $method = $reflection->getMethod('getConfigurableAttributes');
    $method->setAccessible(true);

    $result = $method->invokeArgs($action, [$property]);

    expect($result)->toBeCollection()->toHaveCount(1);
    expect($result->map->getName()->all())->toBe([Alpha::class]);
});

test('getConfigurableAttributes only includes attributes with a rule configurator', function () {
    #[Attribute]
    class AttributeWithoutRuleConfigurator extends ValidationAttribute
    {
        public static function keyword(): string
        {
            return 'alpha';
        }

        public static function create(string ...$parameters): static
        {
            return new static;
        }
    }

    $this->class->addStringProperty('name', [Alpha::class, AttributeWithoutRuleConfigurator::class]);

    $property = $this->class->getClassProperty('name');

    $action = new ApplyRuleConfigurationsToSchema;

    $reflection = new ReflectionClass($action);
    $method = $reflection->getMethod('getConfigurableAttributes');
    $method->setAccessible(true);

    $result = $method->invokeArgs($action, [$property]);

    expect($result)->toBeCollection()->toHaveCount(1);
    expect($result->map->getName()->all())->toBe([Alpha::class]);
});

test('getApplicableContracts returns the correct contracts', function ($type, $contracts) {
    $this->class->addProperty($type, 'name');

    $property = $this->class->getClassProperty('name');
    $schema = MakeSchemaForReflectionType::run($property->getReflectionType());
    $action = new ApplyRuleConfigurationsToSchema;

    $reflection = new ReflectionClass($action);
    $method = $reflection->getMethod('getApplicableContracts');
    $method->setAccessible(true);

    $result = $method->invokeArgs($action, [$schema]);

    expect($result->all())->toBe($contracts);
})->with([
    ['array', [ConfiguresAnySchema::class, ConfiguresArraySchema::class]],
    ['bool', [ConfiguresAnySchema::class, ConfiguresBooleanSchema::class]],
    ['int', [ConfiguresAnySchema::class, ConfiguresIntegerSchema::class, ConfiguresNumberSchema::class]],
    ['float', [ConfiguresAnySchema::class, ConfiguresNumberSchema::class]],
    ['null', [ConfiguresAnySchema::class]],
    ['object', [ConfiguresAnySchema::class, ConfiguresObjectSchema::class]],
    ['string', [ConfiguresAnySchema::class, ConfiguresStringSchema::class]],
]);

it('returns the correct contracts for a class wrapper', function () {
    $schema = ObjectSchema::make();

    $action = new ApplyRuleConfigurationsToSchema;

    $reflection = new ReflectionClass($action);
    $method = $reflection->getMethod('getApplicableContracts');
    $method->setAccessible(true);

    $result = $method->invokeArgs($action, [$schema]);

    expect($result)->toBeCollection()->toHaveCount(2);
    expect($result->all())->toBe([ConfiguresAnySchema::class, ConfiguresObjectSchema::class]);
});

it('gets the correct methods for a contract', function ($contract, $methods) {
    $action = new ApplyRuleConfigurationsToSchema;

    $reflection = new ReflectionClass($action);
    $method = $reflection->getMethod('getContractMethods');
    $method->setAccessible(true);

    $result = $method->invokeArgs($action, [$contract]);

    expect($result->all())->toBe($methods);
})->with([
    [ConfiguresAnySchema::class, ['configureSchema']],
    [ConfiguresArraySchema::class, ['configureArraySchema']],
    [ConfiguresBooleanSchema::class, ['configureBooleanSchema']],
    [ConfiguresIntegerSchema::class, ['configureIntegerSchema', 'configureNumberSchema']],
    [ConfiguresNumberSchema::class, ['configureNumberSchema']],
    [ConfiguresObjectSchema::class, ['configureObjectSchema']],
    [ConfiguresStringSchema::class, ['configureStringSchema']],
]);
