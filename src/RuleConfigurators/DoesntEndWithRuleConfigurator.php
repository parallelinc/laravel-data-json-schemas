<?php

namespace BasilLangevin\LaravelDataJsonSchemas\RuleConfigurators;

use BasilLangevin\LaravelDataJsonSchemas\RuleConfigurators\Contracts\ConfiguresStringSchema;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\StringSchema;
use BasilLangevin\LaravelDataJsonSchemas\Support\AttributeWrapper;
use BasilLangevin\LaravelDataJsonSchemas\Support\PropertyWrapper;

class DoesntEndWithRuleConfigurator implements ConfiguresStringSchema
{
    public static function configureStringSchema(
        StringSchema $schema,
        PropertyWrapper $property,
        AttributeWrapper $attribute
    ): StringSchema {
        /** @var array<int, string> $values */
        $values = $attribute->getValue();

        $regexValues = collect($values)
            ->map(fn (string $value) => preg_quote($value, '/'))
            ->join('|');

        $pattern = sprintf('/^(?!.*(%s)$).*$/', $regexValues);

        $list = collect($values)
            ->map(fn ($value) => sprintf('"%s"', $value))
            ->join(', ', ' or ');

        return $schema
            ->pattern($pattern)
            ->customAnnotation([
                'x-doesnt-end-with' => sprintf('The value must not end with %s.', $list),
            ]);
    }
}
