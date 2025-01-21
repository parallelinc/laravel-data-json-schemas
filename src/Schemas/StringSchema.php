<?php

namespace BasilLangevin\LaravelDataSchemas\Schemas;

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Keywords\Keyword;
use BasilLangevin\LaravelDataSchemas\Keywords\String\MaxLengthKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\String\MinLengthKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\String\PatternKeyword;
use BasilLangevin\LaravelDataSchemas\Schemas\Concerns\PrimitiveSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\Contracts\Schema;

class StringSchema implements Schema
{
    use PrimitiveSchema;

    public static DataType $type = DataType::String;

    public static array $keywords = [
        Keyword::ANNOTATION_KEYWORDS,
        Keyword::GENERAL_KEYWORDS,
        Keyword::COMPOSITION_KEYWORDS,
        MaxLengthKeyword::class,
        MinLengthKeyword::class,
        PatternKeyword::class,
    ];
}
