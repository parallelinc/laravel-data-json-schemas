<?php

namespace BasilLangevin\LaravelDataJsonSchemas\RuleConfigurators;

use BasilLangevin\LaravelDataJsonSchemas\RuleConfigurators\Contracts\ConfiguresAnySchema;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\Contracts\Schema;
use BasilLangevin\LaravelDataJsonSchemas\Support\AttributeWrapper;
use BasilLangevin\LaravelDataJsonSchemas\Support\Contracts\EntityWrapper;

class ConfirmedRuleConfigurator implements ConfiguresAnySchema
{
    public static function configureSchema(
        Schema $schema,
        EntityWrapper $entity,
        AttributeWrapper $attribute
    ): Schema {
        $matchedProperty = $entity->getName().'_confirmed';

        return $schema->customAnnotation(
            'matches',
            sprintf('The value must be the same as the value of the %s property.', $matchedProperty),
        );
    }
}
