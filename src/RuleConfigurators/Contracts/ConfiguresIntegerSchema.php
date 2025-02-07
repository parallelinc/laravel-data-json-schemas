<?php

namespace BasilLangevin\LaravelDataJsonSchemas\RuleConfigurators\Contracts;

use BasilLangevin\LaravelDataJsonSchemas\Schemas\IntegerSchema;
use BasilLangevin\LaravelDataJsonSchemas\Support\AttributeWrapper;
use BasilLangevin\LaravelDataJsonSchemas\Support\PropertyWrapper;

interface ConfiguresIntegerSchema extends ConfiguresSchema
{
    public static function configureIntegerSchema(
        IntegerSchema $schema,
        PropertyWrapper $property,
        AttributeWrapper $attribute
    ): IntegerSchema;
}
