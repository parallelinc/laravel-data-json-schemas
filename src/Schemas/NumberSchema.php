<?php

namespace BasilLangevin\LaravelDataJsonSchemas\Schemas;

use BasilLangevin\LaravelDataJsonSchemas\Enums\DataType;
use BasilLangevin\LaravelDataJsonSchemas\Keywords\Keyword;
use BasilLangevin\LaravelDataJsonSchemas\Keywords\Number\ExclusiveMaximumKeyword;
use BasilLangevin\LaravelDataJsonSchemas\Keywords\Number\ExclusiveMinimumKeyword;
use BasilLangevin\LaravelDataJsonSchemas\Keywords\Number\MaximumKeyword;
use BasilLangevin\LaravelDataJsonSchemas\Keywords\Number\MinimumKeyword;
use BasilLangevin\LaravelDataJsonSchemas\Keywords\Number\MultipleOfKeyword;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\Concerns\SingleTypeSchemaTrait;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\Contracts\SingleTypeSchema;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\DocBlockAnnotations\AnnotationKeywordMethodAnnotations;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\DocBlockAnnotations\CompositionKeywordMethodAnnotations;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\DocBlockAnnotations\GeneralKeywordMethodAnnotations;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\DocBlockAnnotations\NumberSchemaKeywordMethodAnnotations;

class NumberSchema implements SingleTypeSchema
{
    use AnnotationKeywordMethodAnnotations;
    use CompositionKeywordMethodAnnotations;
    use GeneralKeywordMethodAnnotations;
    use NumberSchemaKeywordMethodAnnotations;
    use SingleTypeSchemaTrait;

    public static DataType $type = DataType::Number;

    /**
     * @var array<class-string<Keyword>|array<class-string<Keyword>>>
     */
    public static array $keywords = [
        Keyword::ANNOTATION_KEYWORDS,
        Keyword::GENERAL_KEYWORDS,
        Keyword::COMPOSITION_KEYWORDS,
        MultipleOfKeyword::class,
        MaximumKeyword::class,
        ExclusiveMaximumKeyword::class,
        MinimumKeyword::class,
        ExclusiveMinimumKeyword::class,
    ];
}
