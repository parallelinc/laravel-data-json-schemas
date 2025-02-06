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

class MaxRuleConfigurator implements ConfiguresArraySchema, ConfiguresNumberSchema, ConfiguresObjectSchema, ConfiguresStringSchema
{
    public static function configureArraySchema(
        ArraySchema $schema,
        PropertyWrapper $property,
        AttributeWrapper $attribute
    ): ArraySchema {
        /** @var int $maxItems */
        $maxItems = $attribute->getValue();

        return $schema->maxItems($maxItems);
    }

    public static function configureNumberSchema(
        NumberSchema $schema,
        PropertyWrapper $property,
        AttributeWrapper $attribute
    ): NumberSchema {
        /** @var int $max */
        $max = $attribute->getValue();

        return $schema->maximum($max);
    }

    public static function configureObjectSchema(
        ObjectSchema $schema,
        EntityWrapper $entity,
        AttributeWrapper $attribute
    ): ObjectSchema {
        /** @var int $maxProperties */
        $maxProperties = $attribute->getValue();

        return $schema->maxProperties($maxProperties);
    }

    public static function configureStringSchema(
        StringSchema $schema,
        PropertyWrapper $property,
        AttributeWrapper $attribute
    ): StringSchema {
        /** @var int $maxLength */
        $maxLength = $attribute->getValue();

        return $schema->maxLength($maxLength);
    }
}
