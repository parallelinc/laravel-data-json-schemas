<?php

namespace BasilLangevin\LaravelDataJsonSchemas\RuleConfigurators\Concerns;

use Carbon\Carbon;

trait FormatsDate
{
    /**
     * Format the given value as an ISO 8601 date string.
     *
     * Unless a timezone is specified, we use the Zulu time format.
     */
    protected static function formatDate(mixed $value): string
    {
        /** @phpstan-ignore argument.type */
        $value = new Carbon($value);

        if ($value->isUtc()) {
            return $value->toIso8601ZuluString();
        }

        return $value->toIso8601String();
    }
}
