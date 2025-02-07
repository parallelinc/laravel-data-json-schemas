<?php

namespace BasilLangevin\LaravelDataJsonSchemas\RuleConfigurators;

use BasilLangevin\LaravelDataJsonSchemas\RuleConfigurators\Contracts\ConfiguresNumberSchema;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\NumberSchema;
use BasilLangevin\LaravelDataJsonSchemas\Support\AttributeWrapper;
use BasilLangevin\LaravelDataJsonSchemas\Support\PropertyWrapper;

class MaxDigitsRuleConfigurator implements ConfiguresNumberSchema
{
    public static function configureNumberSchema(
        NumberSchema $schema,
        PropertyWrapper $property,
        AttributeWrapper $attribute
    ): NumberSchema {
        /** @var int $maxDigits */
        $maxDigits = $attribute->getValue();
        $max = pow(10, $maxDigits) - 1;

        return $schema->maximum($max)
            ->customAnnotation('max-digits', sprintf('The value must not have more than %d digits.', $maxDigits));
    }
}
