<?php

namespace BasilLangevin\LaravelDataJsonSchemas\RuleConfigurators\Contracts;

use BasilLangevin\LaravelDataJsonSchemas\Schemas\ObjectSchema;
use BasilLangevin\LaravelDataJsonSchemas\Support\AttributeWrapper;
use BasilLangevin\LaravelDataJsonSchemas\Support\Contracts\EntityWrapper;

interface ConfiguresObjectSchema extends ConfiguresSchema
{
    public static function configureObjectSchema(
        ObjectSchema $schema,
        EntityWrapper $entity,
        AttributeWrapper $attribute
    ): ObjectSchema;
}
