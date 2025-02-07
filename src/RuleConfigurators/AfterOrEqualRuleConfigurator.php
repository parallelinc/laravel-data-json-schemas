<?php

namespace BasilLangevin\LaravelDataJsonSchemas\RuleConfigurators;

use BasilLangevin\LaravelDataJsonSchemas\Enums\Format;
use BasilLangevin\LaravelDataJsonSchemas\RuleConfigurators\Concerns\ParsesDateString;
use BasilLangevin\LaravelDataJsonSchemas\RuleConfigurators\Contracts\ConfiguresStringSchema;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\StringSchema;
use BasilLangevin\LaravelDataJsonSchemas\Support\AttributeWrapper;
use BasilLangevin\LaravelDataJsonSchemas\Support\PropertyWrapper;

class AfterOrEqualRuleConfigurator implements ConfiguresStringSchema
{
    use ParsesDateString;

    public static function configureStringSchema(
        StringSchema $schema,
        PropertyWrapper $property,
        AttributeWrapper $attribute
    ): StringSchema {
        $dateString = self::parseDateString($property, $attribute->getValue());
        $annotation = sprintf('The value must be after or equal to %s.', $dateString);

        return $schema->format(Format::DateTime)
            ->customAnnotation('date-after-or-equal', $annotation);
    }
}
