<?php

namespace BasilLangevin\LaravelDataJsonSchemas\Keywords;

use BasilLangevin\LaravelDataJsonSchemas\Keywords\Annotation\CustomAnnotationKeyword;
use BasilLangevin\LaravelDataJsonSchemas\Keywords\Annotation\DefaultKeyword;
use BasilLangevin\LaravelDataJsonSchemas\Keywords\Annotation\DescriptionKeyword;
use BasilLangevin\LaravelDataJsonSchemas\Keywords\Annotation\DialectKeyword;
use BasilLangevin\LaravelDataJsonSchemas\Keywords\Annotation\TitleKeyword;
use BasilLangevin\LaravelDataJsonSchemas\Keywords\Composition\NotKeyword;
use BasilLangevin\LaravelDataJsonSchemas\Keywords\General\ConstKeyword;
use BasilLangevin\LaravelDataJsonSchemas\Keywords\General\EnumKeyword;
use BasilLangevin\LaravelDataJsonSchemas\Keywords\General\FormatKeyword;
use BasilLangevin\LaravelDataJsonSchemas\Keywords\General\TypeKeyword;
use Illuminate\Support\Collection;

/**
 * A keyword is a class that adds a specific property to a Schema.
 *
 * When the Schema type supports a keyword, the keyword can be
 * set by calling the keyword's name as a camelCased method
 * with the values defined in the keyword's constructor.
 *
 * @example
 * ```php
 * $schema = StringSchema::make()->minLength(10);
 * ```
 *
 * Similarly, the value(s) of the keyword can be retrieved by
 * calling the keyword's name prefixed by "get" as a camel
 * cased method. This returns every value that was set.
 * @example
 * ```php
 * $value = $schema->getMinLength();
 * ```
 */
abstract class Keyword
{
    const ANNOTATION_KEYWORDS = [
        DialectKeyword::class,
        TitleKeyword::class,
        DescriptionKeyword::class,
        DefaultKeyword::class,
        CustomAnnotationKeyword::class,
    ];

    const GENERAL_KEYWORDS = [
        TypeKeyword::class,
        EnumKeyword::class,
        ConstKeyword::class,
        FormatKeyword::class,
    ];

    const COMPOSITION_KEYWORDS = [
        NotKeyword::class,
    ];

    /**
     * A custom name for the method that sets the keyword value.
     * The method name is automatically generated if not set.
     */
    public static string $method;

    /**
     * Get the name of the method that can be called to set the
     * value of the keyword on any schema type that uses it.
     */
    public static function method(): string
    {
        if (isset(static::$method)) {
            return static::$method;
        }

        return str(class_basename(static::class))
            ->beforeLast('Keyword')
            ->camel()
            ->value();
    }

    /**
     * Get the value of the keyword.
     */
    abstract public function get(): mixed;

    /**
     * Add the definition for the keyword to the given schema.
     *
     * @param  Collection<string, mixed>  $schema
     * @return Collection<string, mixed>
     */
    abstract public function apply(Collection $schema): Collection;
}
