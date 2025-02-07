<?php

namespace BasilLangevin\LaravelDataJsonSchemas\RuleConfigurators;

use BasilLangevin\LaravelDataJsonSchemas\RuleConfigurators\Concerns\ResolvesPropertyName;
use BasilLangevin\LaravelDataJsonSchemas\RuleConfigurators\Contracts\ConfiguresArraySchema;
use BasilLangevin\LaravelDataJsonSchemas\RuleConfigurators\Contracts\ConfiguresNumberSchema;
use BasilLangevin\LaravelDataJsonSchemas\RuleConfigurators\Contracts\ConfiguresObjectSchema;
use BasilLangevin\LaravelDataJsonSchemas\RuleConfigurators\Contracts\ConfiguresStringSchema;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\ArraySchema;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\NumberSchema;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\ObjectSchema;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\StringSchema;
use BasilLangevin\LaravelDataJsonSchemas\Support\AttributeWrapper;
use BasilLangevin\LaravelDataJsonSchemas\Support\Contracts\EntityWrapper;
use BasilLangevin\LaravelDataJsonSchemas\Support\PropertyWrapper;

class LessThanRuleConfigurator implements ConfiguresArraySchema, ConfiguresNumberSchema, ConfiguresObjectSchema, ConfiguresStringSchema
{
    use ResolvesPropertyName;

    public static function configureArraySchema(
        ArraySchema $schema,
        PropertyWrapper $property,
        AttributeWrapper $attribute
    ): ArraySchema {
        if (is_int($attribute->getValue())) {
            return $schema->maxItems($attribute->getValue() - 1);
        }

        $property = self::resolvePropertyName($attribute);

        return $schema->customAnnotation('x-less-than', sprintf('The value must have fewer items than the %s property.', $property));
    }

    public static function configureNumberSchema(
        NumberSchema $schema,
        PropertyWrapper $property,
        AttributeWrapper $attribute
    ): NumberSchema {
        if (is_int($attribute->getValue())) {
            return $schema->exclusiveMaximum($attribute->getValue());
        }

        $property = self::resolvePropertyName($attribute);

        return $schema->customAnnotation('x-less-than', sprintf('The value must be less than the value of %s.', $property));
    }

    public static function configureObjectSchema(
        ObjectSchema $schema,
        EntityWrapper $entity,
        AttributeWrapper $attribute
    ): ObjectSchema {
        if (is_int($attribute->getValue())) {
            return $schema->maxProperties($attribute->getValue() - 1);
        }

        $property = self::resolvePropertyName($attribute);

        return $schema->customAnnotation('x-less-than', sprintf('The value must have fewer properties than the %s property.', $property));
    }

    public static function configureStringSchema(
        StringSchema $schema,
        PropertyWrapper $property,
        AttributeWrapper $attribute
    ): StringSchema {
        if (is_int($attribute->getValue())) {
            return $schema->maxLength($attribute->getValue() - 1);
        }

        $property = self::resolvePropertyName($attribute);

        return $schema->customAnnotation('x-less-than', sprintf('The value must have fewer characters than the value of %s.', $property));
    }
}
