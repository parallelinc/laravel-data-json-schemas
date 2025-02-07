<?php

namespace BasilLangevin\LaravelDataJsonSchemas\RuleConfigurators\Concerns;

use BasilLangevin\LaravelDataJsonSchemas\Support\AttributeWrapper;
use Spatie\LaravelData\Support\Validation\References\FieldReference;

trait ResolvesPropertyName
{
    /**
     * Resolve the name of a sibling property.
     */
    protected static function resolvePropertyName(AttributeWrapper $attribute): string
    {
        /** @var FieldReference $field */
        $field = $attribute->getValue();

        return $field->name;
    }
}
