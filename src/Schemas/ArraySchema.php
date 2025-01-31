<?php

namespace BasilLangevin\LaravelDataSchemas\Schemas;

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Keywords\Array\ItemsKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\Array\MaxItemsKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\Array\MinItemsKeyword;
use BasilLangevin\LaravelDataSchemas\Keywords\Keyword;
use BasilLangevin\LaravelDataSchemas\Schemas\Concerns\SingleTypeSchemaTrait;
use BasilLangevin\LaravelDataSchemas\Schemas\Contracts\SingleTypeSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\DocBlockAnnotations\AnnotationKeywordMethodAnnotations;
use BasilLangevin\LaravelDataSchemas\Schemas\DocBlockAnnotations\ArraySchemaKeywordMethodAnnotations;
use BasilLangevin\LaravelDataSchemas\Schemas\DocBlockAnnotations\CompositionKeywordMethodAnnotations;
use BasilLangevin\LaravelDataSchemas\Schemas\DocBlockAnnotations\GeneralKeywordMethodAnnotations;

class ArraySchema implements SingleTypeSchema
{
    use AnnotationKeywordMethodAnnotations;

    // DocBlock annotations
    use ArraySchemaKeywordMethodAnnotations;
    use CompositionKeywordMethodAnnotations;
    use GeneralKeywordMethodAnnotations;
    use SingleTypeSchemaTrait;

    public static DataType $type = DataType::Array;

    public static array $keywords = [
        Keyword::ANNOTATION_KEYWORDS,
        Keyword::GENERAL_KEYWORDS,
        Keyword::COMPOSITION_KEYWORDS,
        MaxItemsKeyword::class,
        MinItemsKeyword::class,
        ItemsKeyword::class,
    ];

    public function toArray(bool $nested = false): array
    {
        if ($nested || ! $this->tree->hasDefs()) {
            return $this->buildSchema();
        }

        return [
            ...$this->buildSchema(),
            '$defs' => $this->tree->getDefs(),
        ];
    }
}
