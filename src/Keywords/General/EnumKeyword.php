<?php

namespace BasilLangevin\LaravelDataJsonSchemas\Keywords\General;

use BasilLangevin\LaravelDataJsonSchemas\Exceptions\SchemaConfigurationException;
use BasilLangevin\LaravelDataJsonSchemas\Keywords\Contracts\HandlesMultipleInstances;
use BasilLangevin\LaravelDataJsonSchemas\Keywords\Keyword;
use Illuminate\Support\Collection;

class EnumKeyword extends Keyword implements HandlesMultipleInstances
{
    /**
     * @param  string|array<int, int|string|bool|\BackedEnum>  $value
     */
    public function __construct(protected string|array $value)
    {
        if (is_array($value)) {
            $this->validateArray($value);

            return;
        }

        if (! enum_exists($value)) {
            throw new \InvalidArgumentException("Enum '{$value}' is not a valid enum.");
        }

        $reflection = new \ReflectionEnum($value);

        if (! $reflection->isBacked()) {
            throw new \InvalidArgumentException("Enum '{$value}' is not a backed enum. Only backed enums are supported.");
        }
    }

    /**
     * {@inheritdoc}
     *
     * @return string|array<int, int|string|bool|\BackedEnum>
     */
    public function get(): string|array
    {
        return $this->value;
    }

    /**
     * {@inheritdoc}
     */
    public function apply(Collection $schema): Collection
    {
        $keywords = array_filter([
            'enum' => $this->getValues(),
            'x-enum-values' => $this->getValueDescriptions(),
        ]);

        return $schema->merge($keywords);
    }

    /**
     * {@inheritdoc}
     */
    public static function applyMultiple(Collection $schema, Collection $instances): Collection
    {
        $values = $instances->map(fn ($instance) => $instance->getValues());

        $commonValues = $values
            // The skip(1) is not technically needed, but it improves performance.
            ->skip(1) // @pest-mutate-ignore
            ->reduce(function (Collection $result, array $instanceValues) {
                return $result->intersect($instanceValues);
            }, collect($values->first()))
            ->values();

        if ($commonValues->isEmpty()) {
            throw new SchemaConfigurationException('Multiple enums were set with no overlapping values.');
        }

        return $schema->merge([
            'enum' => $commonValues->toArray(),
        ]);
    }

    /**
     * Validate the array value.
     *
     * @param  array<int, int|string|bool|\BackedEnum>  $value
     */
    protected function validateArray(array $value): void
    {
        $enumValues = collect($value)->filter(fn ($value) => is_object($value));

        /** @phpstan-ignore function.alreadyNarrowedType */
        if ($enumValues->some(fn ($value) => ! property_exists($value, 'value'))) {
            throw new SchemaConfigurationException('Non-backed enum values are not supported.');
        }
    }

    /**
     * Check if the value is an enum.
     *
     * @phpstan-assert-if-true class-string<\BackedEnum> $this->get()
     */
    protected function isEnum(): bool
    {
        return is_string($this->get()) && enum_exists($this->get());
    }

    /**
     * @return array<int, int|string|bool>
     */
    protected function getValues(): array
    {
        /** @var array<int, \BackedEnum|int|string|bool> $values */
        $values = $this->isEnum() ? $this->get()::cases() : $this->get();

        return collect($values)
            ->map(fn ($value) => is_object($value) ? $value->value : $value)
            ->all();
    }

    /**
     * Get the value descriptions for the enum if the enum backing type is an integer.
     *
     * @return array<string, int>|null
     */
    protected function getValueDescriptions(): ?array
    {
        if (! $this->isEnum()) {
            return null;
        }

        $reflection = new \ReflectionEnum($this->get());

        /** @var \ReflectionNamedType $backingType */
        $backingType = $reflection->getBackingType();

        if ($backingType->getName() !== 'int') {
            return null;
        }

        /** @var array<string, int> */
        $result = collect($this->get()::cases())->mapWithKeys(function ($value) {
            return [$value->name => $value->value];
        })->all();

        return $result;
    }
}
