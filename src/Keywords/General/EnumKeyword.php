<?php

namespace BasilLangevin\LaravelDataSchemas\Keywords\General;

use BasilLangevin\LaravelDataSchemas\Exceptions\SchemaConfigurationException;
use BasilLangevin\LaravelDataSchemas\Keywords\Contracts\HandlesMultipleInstances;
use BasilLangevin\LaravelDataSchemas\Keywords\Keyword;
use Illuminate\Support\Collection;

class EnumKeyword extends Keyword implements HandlesMultipleInstances
{
    /**
     * @param  string|array<int, int|string>  $value
     */
    public function __construct(protected string|array $value)
    {
        if (is_array($value)) {
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
     * @return string|array<int, int|string>
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
     * Check if the value is an enum.
     *
     * @phpstan-assert-if-true class-string<\BackedEnum> $this->get()
     */
    protected function isEnum(): bool
    {
        return is_string($this->get()) && enum_exists($this->get());
    }

    /**
     * @return array<int, int|string>
     */
    protected function getValues(): array
    {
        /** @var array<int, \BackedEnum|int|string> $values */
        $values = $this->isEnum() ? $this->get()::cases() : $this->get();

        return collect($values)->map(function ($value) {
            if (! is_object($value)) {
                return $value;
            }

            if (property_exists($value, 'value')) {
                return $value->value;
            }

            throw new SchemaConfigurationException('Non-backed enum values are not supported.');
        })->toArray();
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

        if ($reflection->getBackingType()->getName() !== 'int') {
            return null;
        }

        return collect($this->get()::cases())->mapWithKeys(function ($value) {
            return [$value->name => $value->value];
        })->toArray();
    }
}
