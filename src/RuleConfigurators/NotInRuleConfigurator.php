<?php

namespace BasilLangevin\LaravelDataSchemas\RuleConfigurators;

use BasilLangevin\LaravelDataSchemas\RuleConfigurators\Contracts\ConfiguresNumberSchema;
use BasilLangevin\LaravelDataSchemas\RuleConfigurators\Contracts\ConfiguresStringSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\NumberSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\StringSchema;
use BasilLangevin\LaravelDataSchemas\Support\AttributeWrapper;
use BasilLangevin\LaravelDataSchemas\Support\PropertyWrapper;

class NotInRuleConfigurator implements ConfiguresNumberSchema, ConfiguresStringSchema
{
    public static function configureNumberSchema(
        NumberSchema $schema,
        PropertyWrapper $property,
        AttributeWrapper $attribute
    ): NumberSchema {
        /** @var array<int, string|int> $values */
        $values = $attribute->getValue();

        return $schema->not(fn (NumberSchema $schema) => $schema->enum($values));
    }

    public static function configureStringSchema(
        StringSchema $schema,
        PropertyWrapper $property,
        AttributeWrapper $attribute
    ): StringSchema {
        /** @var array<int, string|int> $values */
        $values = $attribute->getValue();

        return $schema->not(fn (StringSchema $schema) => $schema->enum($values));
    }
}
