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

class SizeRuleConfigurator implements ConfiguresArraySchema, ConfiguresNumberSchema, ConfiguresObjectSchema, ConfiguresStringSchema
{
    public static function configureArraySchema(
        ArraySchema $schema,
        PropertyWrapper $property,
        AttributeWrapper $attribute
    ): ArraySchema {
        /** @var int $size */
        $size = $attribute->getValue();

        return $schema->minItems($size)
            ->maxItems($size);
    }

    public static function configureNumberSchema(
        NumberSchema $schema,
        PropertyWrapper $property,
        AttributeWrapper $attribute
    ): NumberSchema {
        /** @var int $size */
        $size = $attribute->getValue();

        return $schema->minimum($size)
            ->maximum($size);
    }

    public static function configureObjectSchema(
        ObjectSchema $schema,
        EntityWrapper $property,
        AttributeWrapper $attribute
    ): ObjectSchema {
        /** @var int $size */
        $size = $attribute->getValue();

        return $schema->minProperties($size)
            ->maxProperties($size);
    }

    public static function configureStringSchema(
        StringSchema $schema,
        PropertyWrapper $property,
        AttributeWrapper $attribute
    ): StringSchema {
        /** @var int $size */
        $size = $attribute->getValue();

        return $schema->minLength($size)
            ->maxLength($size);
    }
}
