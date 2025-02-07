<?php

namespace BasilLangevin\LaravelDataJsonSchemas\RuleConfigurators;

use BasilLangevin\LaravelDataJsonSchemas\RuleConfigurators\Contracts\ConfiguresStringSchema;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\StringSchema;
use BasilLangevin\LaravelDataJsonSchemas\Support\AttributeWrapper;
use BasilLangevin\LaravelDataJsonSchemas\Support\PropertyWrapper;

class DateFormatRuleConfigurator implements ConfiguresStringSchema
{
    public static function configureStringSchema(
        StringSchema $schema,
        PropertyWrapper $property,
        AttributeWrapper $attribute
    ): StringSchema {
        /** @var array<int, string> $formats */
        $formats = $attribute->getValue();

        $formatString = collect($formats)
            ->map(fn (string $format) => sprintf('"%s"', $format))
            ->join(', ', ' or ');

        $annotation = sprintf('The value must match the format %s.', $formatString);

        return $schema->customAnnotation('date-format', $annotation);
    }
}
