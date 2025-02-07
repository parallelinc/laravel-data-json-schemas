<?php

namespace BasilLangevin\LaravelDataJsonSchemas\RuleConfigurators;

use BasilLangevin\LaravelDataJsonSchemas\RuleConfigurators\Concerns\ResolvesPropertyName;
use BasilLangevin\LaravelDataJsonSchemas\RuleConfigurators\Contracts\ConfiguresAnySchema;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\Contracts\Schema;
use BasilLangevin\LaravelDataJsonSchemas\Support\AttributeWrapper;
use BasilLangevin\LaravelDataJsonSchemas\Support\Contracts\EntityWrapper;

class DifferentRuleConfigurator implements ConfiguresAnySchema
{
    use ResolvesPropertyName;

    public static function configureSchema(
        Schema $schema,
        EntityWrapper $entity,
        AttributeWrapper $attribute
    ): Schema {
        $otherField = self::resolvePropertyName($attribute);

        return $schema->customAnnotation(
            'different-than',
            sprintf('The value must be different from the value of the %s property.', $otherField),
        );
    }
}
