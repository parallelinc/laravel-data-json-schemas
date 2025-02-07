<?php

namespace BasilLangevin\LaravelDataJsonSchemas\RuleConfigurators;

use BasilLangevin\LaravelDataJsonSchemas\RuleConfigurators\Contracts\ConfiguresNumberSchema;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\NumberSchema;
use BasilLangevin\LaravelDataJsonSchemas\Support\AttributeWrapper;
use BasilLangevin\LaravelDataJsonSchemas\Support\PropertyWrapper;

class MinDigitsRuleConfigurator implements ConfiguresNumberSchema
{
    public static function configureNumberSchema(
        NumberSchema $schema,
        PropertyWrapper $property,
        AttributeWrapper $attribute
    ): NumberSchema {
        /** @var int $minDigits */
        $minDigits = $attribute->getValue();
        $min = pow(10, $minDigits - 1);

        return $schema->minimum($min)
            ->customAnnotation('min-digits', sprintf('The value must have at least %d digits.', $minDigits));
    }
}
