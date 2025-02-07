<?php

namespace BasilLangevin\LaravelDataJsonSchemas\RuleConfigurators\Contracts;

use BasilLangevin\LaravelDataJsonSchemas\Schemas\BooleanSchema;
use BasilLangevin\LaravelDataJsonSchemas\Support\AttributeWrapper;
use BasilLangevin\LaravelDataJsonSchemas\Support\PropertyWrapper;

interface ConfiguresBooleanSchema extends ConfiguresSchema
{
    public static function configureBooleanSchema(
        BooleanSchema $schema,
        PropertyWrapper $property,
        AttributeWrapper $attribute
    ): BooleanSchema;
}
