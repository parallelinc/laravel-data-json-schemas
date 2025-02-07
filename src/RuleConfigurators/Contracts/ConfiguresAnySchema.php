<?php

namespace BasilLangevin\LaravelDataJsonSchemas\RuleConfigurators\Contracts;

use BasilLangevin\LaravelDataJsonSchemas\Schemas\Contracts\Schema;
use BasilLangevin\LaravelDataJsonSchemas\Support\AttributeWrapper;
use BasilLangevin\LaravelDataJsonSchemas\Support\Contracts\EntityWrapper;

interface ConfiguresAnySchema extends ConfiguresSchema
{
    public static function configureSchema(
        Schema $schema,
        EntityWrapper $entity,
        AttributeWrapper $attribute
    ): Schema;
}
