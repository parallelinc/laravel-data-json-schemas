<?php

namespace BasilLangevin\LaravelDataSchemas\RuleConfigurators;

use BasilLangevin\LaravelDataSchemas\RuleConfigurators\Contracts\ConfiguresObjectSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\ObjectSchema;
use BasilLangevin\LaravelDataSchemas\Support\AttributeWrapper;
use BasilLangevin\LaravelDataSchemas\Support\Contracts\EntityWrapper;

class ArrayTypeRuleConfigurator implements ConfiguresObjectSchema
{
    public static function configureObjectSchema(
        ObjectSchema $schema,
        EntityWrapper $entity,
        AttributeWrapper $attribute
    ): ObjectSchema {
        /** @var array<int, string> $properties */
        $properties = $attribute->getValue();

        return $schema->required($properties);
    }
}
