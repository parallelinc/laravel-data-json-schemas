<?php

namespace BasilLangevin\LaravelDataJsonSchemas\Schemas;

use BasilLangevin\LaravelDataJsonSchemas\Enums\DataType;
use BasilLangevin\LaravelDataJsonSchemas\Keywords\Keyword;
use BasilLangevin\LaravelDataJsonSchemas\Keywords\Object\MaxPropertiesKeyword;
use BasilLangevin\LaravelDataJsonSchemas\Keywords\Object\MinPropertiesKeyword;
use BasilLangevin\LaravelDataJsonSchemas\Keywords\Object\PropertiesKeyword;
use BasilLangevin\LaravelDataJsonSchemas\Keywords\Object\RequiredKeyword;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\Concerns\SingleTypeSchemaTrait;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\Contracts\SingleTypeSchema;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\DocBlockAnnotations\AnnotationKeywordMethodAnnotations;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\DocBlockAnnotations\CompositionKeywordMethodAnnotations;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\DocBlockAnnotations\GeneralKeywordMethodAnnotations;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\DocBlockAnnotations\ObjectSchemaKeywordMethodAnnotations;
use Spatie\LaravelData\Data;

class ObjectSchema implements SingleTypeSchema
{
    use AnnotationKeywordMethodAnnotations;
    use CompositionKeywordMethodAnnotations;
    use GeneralKeywordMethodAnnotations;
    use ObjectSchemaKeywordMethodAnnotations;
    use SingleTypeSchemaTrait;

    public static DataType $type = DataType::Object;

    /**
     * @var array<class-string<Keyword>|array<class-string<Keyword>>>
     */
    public static array $keywords = [
        Keyword::ANNOTATION_KEYWORDS,
        Keyword::GENERAL_KEYWORDS,
        Keyword::COMPOSITION_KEYWORDS,
        PropertiesKeyword::class,
        RequiredKeyword::class,
        MaxPropertiesKeyword::class,
        MinPropertiesKeyword::class,
    ];

    /**
     * The class of the object that this schema represents.
     *
     * @var class-string<Data>
     */
    protected string $class;

    /**
     * Set the class of the object that this schema represents.
     *
     * @param  class-string<Data>  $class
     */
    public function class(string $class): static
    {
        $this->class = $class;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray(bool $nested = false): array
    {
        if ($nested && isset($this->class) && $this->tree->hasMultiple($this->class)) {
            return ['$ref' => $this->tree->getRefName($this->class)];
        }

        if ($nested || ! $this->tree->hasDefs()) {
            return $this->buildSchema();
        }

        return [
            ...$this->buildSchema(),
            '$defs' => $this->tree->getDefs(),
        ];
    }
}
