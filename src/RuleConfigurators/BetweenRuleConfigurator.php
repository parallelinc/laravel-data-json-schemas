<?php

namespace BasilLangevin\LaravelDataSchemas\RuleConfigurators;

use BasilLangevin\LaravelDataSchemas\RuleConfigurators\Contracts\ConfiguresArraySchema;
use BasilLangevin\LaravelDataSchemas\RuleConfigurators\Contracts\ConfiguresNumberSchema;
use BasilLangevin\LaravelDataSchemas\RuleConfigurators\Contracts\ConfiguresObjectSchema;
use BasilLangevin\LaravelDataSchemas\RuleConfigurators\Contracts\ConfiguresStringSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\ArraySchema;
use BasilLangevin\LaravelDataSchemas\Schemas\NumberSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\ObjectSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\StringSchema;
use BasilLangevin\LaravelDataSchemas\Support\AttributeWrapper;
use BasilLangevin\LaravelDataSchemas\Support\Contracts\EntityWrapper;
use BasilLangevin\LaravelDataSchemas\Support\PropertyWrapper;

class BetweenRuleConfigurator implements ConfiguresArraySchema, ConfiguresNumberSchema, ConfiguresObjectSchema, ConfiguresStringSchema
{
    public static function configureArraySchema(
        ArraySchema $schema,
        PropertyWrapper $property,
        AttributeWrapper $attribute
    ): ArraySchema {
        /** @var array<int, int> $values */
        $values = $attribute->getValue();

        return $schema->minItems($values[0])
            ->maxItems($values[1]);
    }

    public static function configureNumberSchema(
        NumberSchema $schema,
        PropertyWrapper $property,
        AttributeWrapper $attribute
    ): NumberSchema {
        /** @var array<int, int> $values */
        $values = $attribute->getValue();

        return $schema->minimum($values[0])
            ->maximum($values[1]);
    }

    public static function configureObjectSchema(
        ObjectSchema $schema,
        EntityWrapper $property,
        AttributeWrapper $attribute
    ): ObjectSchema {
        /** @var array<int, int> $values */
        $values = $attribute->getValue();

        return $schema->minProperties($values[0])
            ->maxProperties($values[1]);
    }

    public static function configureStringSchema(
        StringSchema $schema,
        PropertyWrapper $property,
        AttributeWrapper $attribute
    ): StringSchema {
        /** @var array<int, int> $values */
        $values = $attribute->getValue();

        return $schema->minLength($values[0])
            ->maxLength($values[1]);
    }
}
