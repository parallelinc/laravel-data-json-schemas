<?php

namespace BasilLangevin\LaravelDataJsonSchemas\Schemas;

use BasilLangevin\LaravelDataJsonSchemas\Enums\DataType;
use BasilLangevin\LaravelDataJsonSchemas\Keywords\Array\ItemsKeyword;
use BasilLangevin\LaravelDataJsonSchemas\Keywords\Array\MaxItemsKeyword;
use BasilLangevin\LaravelDataJsonSchemas\Keywords\Array\MinItemsKeyword;
use BasilLangevin\LaravelDataJsonSchemas\Keywords\Keyword;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\Concerns\SingleTypeSchemaTrait;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\Contracts\SingleTypeSchema;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\DocBlockAnnotations\AnnotationKeywordMethodAnnotations;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\DocBlockAnnotations\ArraySchemaKeywordMethodAnnotations;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\DocBlockAnnotations\CompositionKeywordMethodAnnotations;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\DocBlockAnnotations\GeneralKeywordMethodAnnotations;

class ArraySchema implements SingleTypeSchema
{
    use AnnotationKeywordMethodAnnotations;
    use ArraySchemaKeywordMethodAnnotations;
    use CompositionKeywordMethodAnnotations;
    use GeneralKeywordMethodAnnotations;
    use SingleTypeSchemaTrait;

    public static DataType $type = DataType::Array;

    /**
     * @var array<class-string<Keyword>|array<class-string<Keyword>>>
     */
    public static array $keywords = [
        Keyword::ANNOTATION_KEYWORDS,
        Keyword::GENERAL_KEYWORDS,
        Keyword::COMPOSITION_KEYWORDS,
        MaxItemsKeyword::class,
        MinItemsKeyword::class,
        ItemsKeyword::class,
    ];

    /**
     * {@inheritdoc}
     */
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
