<?php

namespace BasilLangevin\LaravelDataSchemas\RuleConfigurators\Concerns;

use BasilLangevin\LaravelDataSchemas\Support\PropertyWrapper;

trait ParsesDateString
{
    use FormatsDate;

    /**
     * Parse the given value as a date string or sibling property reference.
     *
     * If the value is a string, we check if it's a sibling property name.
     * When it is, the Validator will compare the value to the sibling
     * property's value so we prefix the value with "the value of".
     *
     * Otherwise, we format the value as an ISO 8601 date string.
     */
    protected static function parseDateString(PropertyWrapper $property, mixed $value): string
    {
        if (is_string($value) && $property->siblingNames()->contains($value)) {
            return 'the value of '.$value;
        }

        return self::formatDate($value);
    }
}
