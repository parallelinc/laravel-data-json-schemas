<?php

namespace BasilLangevin\LaravelDataJsonSchemas\RuleConfigurators;

use BasilLangevin\LaravelDataJsonSchemas\RuleConfigurators\Contracts\ConfiguresNumberSchema;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\NumberSchema;
use BasilLangevin\LaravelDataJsonSchemas\Support\AttributeWrapper;
use BasilLangevin\LaravelDataJsonSchemas\Support\PropertyWrapper;

class DigitsBetweenRuleConfigurator implements ConfiguresNumberSchema
{
    public static function configureNumberSchema(
        NumberSchema $schema,
        PropertyWrapper $property,
        AttributeWrapper $attribute
    ): NumberSchema {
        /** @var array<int, int> $values */
        $values = $attribute->getValue();

        [$minDigits, $maxDigits] = $values;
        $min = pow(10, $minDigits - 1);
        $max = pow(10, $maxDigits) - 1;

        return $schema->minimum($min)
            ->maximum($max)
            ->customAnnotation('digits-between', sprintf('The value must have between %d and %d digits.', $minDigits, $maxDigits));
    }
}
