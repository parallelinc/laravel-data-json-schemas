<?php

namespace BasilLangevin\LaravelDataSchemas\RuleConfigurators\Contracts;

use BasilLangevin\LaravelDataSchemas\Schemas\Contracts\Schema;
use BasilLangevin\LaravelDataSchemas\Support\AttributeWrapper;
use BasilLangevin\LaravelDataSchemas\Support\Contracts\EntityWrapper;

interface ConfiguresAnySchema
{
    public static function configureSchema(
        Schema $schema,
        EntityWrapper $entity,
        AttributeWrapper $attribute
    ): Schema;
}
