<?php

namespace BasilLangevin\LaravelDataJsonSchemas\RuleConfigurators;

use BasilLangevin\LaravelDataJsonSchemas\Enums\Format;
use BasilLangevin\LaravelDataJsonSchemas\RuleConfigurators\Concerns\FormatsDate;
use BasilLangevin\LaravelDataJsonSchemas\RuleConfigurators\Contracts\ConfiguresStringSchema;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\StringSchema;
use BasilLangevin\LaravelDataJsonSchemas\Support\AttributeWrapper;
use BasilLangevin\LaravelDataJsonSchemas\Support\PropertyWrapper;

class DateEqualsRuleConfigurator implements ConfiguresStringSchema
{
    use FormatsDate;

    public static function configureStringSchema(
        StringSchema $schema,
        PropertyWrapper $property,
        AttributeWrapper $attribute
    ): StringSchema {
        $dateString = self::formatDate($attribute->getValue());

        return $schema
            ->format(Format::DateTime)
            ->const($dateString);
    }
}
