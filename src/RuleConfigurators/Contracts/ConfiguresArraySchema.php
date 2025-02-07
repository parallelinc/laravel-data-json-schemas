<?php

namespace BasilLangevin\LaravelDataJsonSchemas\RuleConfigurators\Contracts;

use BasilLangevin\LaravelDataJsonSchemas\Schemas\ArraySchema;
use BasilLangevin\LaravelDataJsonSchemas\Support\AttributeWrapper;
use BasilLangevin\LaravelDataJsonSchemas\Support\PropertyWrapper;

interface ConfiguresArraySchema extends ConfiguresSchema
{
    public static function configureArraySchema(
        ArraySchema $schema,
        PropertyWrapper $property,
        AttributeWrapper $attribute
    ): ArraySchema;
}
