<?php

namespace BasilLangevin\LaravelDataJsonSchemas\RuleConfigurators;

use BasilLangevin\LaravelDataJsonSchemas\RuleConfigurators\Contracts\ConfiguresNumberSchema;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\NumberSchema;
use BasilLangevin\LaravelDataJsonSchemas\Support\AttributeWrapper;
use BasilLangevin\LaravelDataJsonSchemas\Support\PropertyWrapper;

class DigitsRuleConfigurator implements ConfiguresNumberSchema
{
    public static function configureNumberSchema(
        NumberSchema $schema,
        PropertyWrapper $property,
        AttributeWrapper $attribute
    ): NumberSchema {
        /** @var int $digits */
        $digits = $attribute->getValue();
        $min = pow(10, $digits - 1);
        $max = pow(10, $digits) - 1;

        return $schema->minimum($min)
            ->maximum($max)
            ->customAnnotation('digits', sprintf('The value must have %d digits.', $digits));
    }
}
