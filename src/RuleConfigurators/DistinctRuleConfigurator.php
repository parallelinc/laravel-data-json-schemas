<?php

namespace BasilLangevin\LaravelDataJsonSchemas\RuleConfigurators;

use BasilLangevin\LaravelDataJsonSchemas\RuleConfigurators\Contracts\ConfiguresAnySchema;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\Contracts\Schema;
use BasilLangevin\LaravelDataJsonSchemas\Support\AttributeWrapper;
use BasilLangevin\LaravelDataJsonSchemas\Support\Contracts\EntityWrapper;

class DistinctRuleConfigurator implements ConfiguresAnySchema
{
    public static function configureSchema(
        Schema $schema,
        EntityWrapper $entity,
        AttributeWrapper $attribute
    ): Schema {
        return $schema->customAnnotation(
            'distinct',
            sprintf('The value of each %s property must be unique.', $entity->getName()),
        );
    }
}
