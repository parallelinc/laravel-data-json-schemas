<?php

namespace BasilLangevin\LaravelDataSchemas\Schemas;

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Keywords\Keyword;
use BasilLangevin\LaravelDataSchemas\Schemas\Concerns\PrimitiveSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\Contracts\Schema;

class BooleanSchema implements Schema
{
    use PrimitiveSchema;

    public static DataType $type = DataType::Boolean;

    public static array $keywords = [
        Keyword::ANNOTATION_KEYWORDS,
        Keyword::GENERAL_KEYWORDS,
        Keyword::COMPOSITION_KEYWORDS,
    ];
}
