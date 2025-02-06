<?php

namespace BasilLangevin\LaravelDataSchemas\Support;

use BasilLangevin\LaravelDataSchemas\Attributes\Contracts\ArrayAttribute;
use BasilLangevin\LaravelDataSchemas\Attributes\Contracts\StringAttribute;
use BasilLangevin\LaravelDataSchemas\RuleConfigurators\Contracts\ConfiguresSchema;
use Illuminate\Support\Arr;
use Spatie\LaravelData\Attributes\Validation\Enum;
use Spatie\LaravelData\Attributes\Validation\In;
use Spatie\LaravelData\Attributes\Validation\NotIn;
use Spatie\LaravelData\Attributes\Validation\StringValidationAttribute;
use Spatie\LaravelData\Attributes\Validation\ValidationAttribute;

class AttributeWrapper
{
    const SUPPORTED_ATTRIBUTES = [
        StringAttribute::class,
        ArrayAttribute::class,
        ValidationAttribute::class,
    ];

    protected object $instance;

    /**
     * @param  \ReflectionAttribute<ValidationAttribute|StringAttribute|ArrayAttribute>  $attribute
     */
    public function __construct(protected \ReflectionAttribute $attribute)
    {
        /** @phpstan-ignore staticMethod.alreadyNarrowedType */
        if (! self::supports($attribute)) {
            /** @var string $name */
            $name = $attribute->getName();

            throw new \InvalidArgumentException("AttributeWrapper does not support the \"{$name}\" attribute.");
        }

        $this->instance = $attribute->newInstance();
    }

    /**
     * Check if the attribute is supported by the AttributeWrapper.
     *
     * @param  \ReflectionAttribute<object>  $attribute
     *
     * @phpstan-assert-if-true \ReflectionAttribute<ValidationAttribute|StringAttribute|ArrayAttribute> $attribute
     */
    public static function supports(\ReflectionAttribute $attribute): bool
    {
        return collect(self::SUPPORTED_ATTRIBUTES)
            /** @phpstan-ignore function.alreadyNarrowedType */
            ->some(fn (string $class) => is_subclass_of($attribute->getName(), $class));
    }

    /**
     * Get the name of the attribute.
     */
    public function getName(): string
    {
        return $this->attribute->getName();
    }

    /**
     * Get the value of the attribute.
     */
    public function getValue(): mixed
    {
        return match (true) {
            // Local attributes
            $this->instance instanceof StringAttribute => $this->instance->getValue(),
            $this->instance instanceof ArrayAttribute => $this->instance->getValue(),

            // Spatie/laravel-data validation attributes
            $this->instance instanceof StringValidationAttribute => $this->getStringValidationAttributeValue(),
            $this->instance instanceof Enum => $this->getInstancePropertyValue('enum'),
            $this->instance instanceof In => $this->getFlattenedInstancePropertyValue('values'),
            $this->instance instanceof NotIn => $this->getFlattenedInstancePropertyValue('values'),

            default => throw new \Exception('Attribute value not supported'),
        };
    }

    protected function getStringValidationAttributeValue(): mixed
    {
        /** @var StringValidationAttribute $attribute */
        $attribute = $this->instance;
        $parameters = $attribute->parameters();

        if ($parameters === []) {
            return null;
        }

        return count($parameters) === 1 ? $parameters[0] : $parameters;
    }

    /**
     * Get the value of a property of the attribute instance.
     */
    protected function getInstancePropertyValue(string $property): mixed
    {
        $reflection = new \ReflectionObject($this->instance);

        return $reflection->getProperty($property)->getValue($this->instance);
    }

    /**
     * Get the value of a property of the attribute instance and flatten it.
     *
     * @return array<int, mixed>
     */
    protected function getFlattenedInstancePropertyValue(string $property): array
    {
        /** @var array<int, mixed> $value */
        $value = $this->getInstancePropertyValue($property);

        /** @var array<int, mixed> $result */
        $result = Arr::flatten($value);

        return $result;
    }

    /**
     * Check if the attribute is a Spatie/laravel-data validation attribute.
     */
    public function isValidationAttribute(): bool
    {
        return $this->instance instanceof ValidationAttribute;
    }

    protected function getRuleConfiguratorClassName(): string
    {
        $namespace = 'BasilLangevin\LaravelDataSchemas\RuleConfigurators';

        return $namespace.'\\'.class_basename($this->getName()).'RuleConfigurator';
    }

    /**
     * Check if the attribute has a rule configurator.
     */
    public function hasRuleConfigurator(): bool
    {
        return class_exists($this->getRuleConfiguratorClassName());
    }

    /**
     * Get the rule configurator class name.
     *
     * @return class-string<ConfiguresSchema>|null
     */
    public function getRuleConfigurator(): ?string
    {
        if (! $this->hasRuleConfigurator()) {
            return null;
        }

        /** @var class-string<ConfiguresSchema> */
        return $this->getRuleConfiguratorClassName();
    }
}
