<?php

namespace BasilLangevin\LaravelDataSchemas\RuleConfigurators;

use BasilLangevin\LaravelDataSchemas\RuleConfigurators\Contracts\ConfiguresNumberSchema;
use BasilLangevin\LaravelDataSchemas\RuleConfigurators\Contracts\ConfiguresStringSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\NumberSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\StringSchema;
use BasilLangevin\LaravelDataSchemas\Support\AttributeWrapper;
use BasilLangevin\LaravelDataSchemas\Support\PropertyWrapper;

class EnumRuleConfigurator implements ConfiguresNumberSchema, ConfiguresStringSchema
{
    public static function configureNumberSchema(
        NumberSchema $schema,
        PropertyWrapper $property,
        AttributeWrapper $attribute
    ): NumberSchema {
        /** @var class-string $enum */
        $enum = $attribute->getValue();

        return $schema->enum($enum);
    }

    public static function configureStringSchema(
        StringSchema $schema,
        PropertyWrapper $property,
        AttributeWrapper $attribute
    ): StringSchema {
        /** @var class-string $enum */
        $enum = $attribute->getValue();

        return $schema->enum($enum);
    }
}
