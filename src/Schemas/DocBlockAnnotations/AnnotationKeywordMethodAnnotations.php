<?php

namespace BasilLangevin\LaravelDataSchemas\Schemas\DocBlockAnnotations;

/**
 * @method static dialect(JsonSchemaDialect $dialect) Set the dialect keyword of the schema.
 * @method JsonSchemaDialect|Collection<int, JsonSchemaDialect> getDialect() Get the value(s) passed to the dialect method.
 *
 * @see BasilLangevin\LaravelDataSchemas\Keywords\Annotation\DialectKeyword
 *
 * @method static title(string $title) Set the title keyword of the schema.
 * @method string|Collection<int, string> getTitle() Get the value(s) passed to the title method.
 *
 * @see BasilLangevin\LaravelDataSchemas\Keywords\Annotation\TitleKeyword
 *
 * @method static description(string $description) Set the description keyword of the schema.
 * @method string|Collection<int, string> getDescription() Get the value(s) passed to the description method.
 *
 * @see BasilLangevin\LaravelDataSchemas\Keywords\Annotation\DescriptionKeyword
 *
 * @method static default(mixed $default) Set the default keyword of the schema.
 * @method mixed|Collection<int, mixed> getDefault() Get the value(s) passed to the default method.
 *
 * @see BasilLangevin\LaravelDataSchemas\Keywords\Annotation\DefaultKeyword
 *
 * @method static customAnnotation(string|array<string, string> $annotation, ?string $value = null) Set a custom annotation on the schema.
 * @method array<string, string>|Collection<int, array<string, string>> getCustomAnnotation() Get the formatted value(s) passed to the customAnnotation method.
 *
 * @see BasilLangevin\LaravelDataSchemas\Keywords\Annotation\CustomAnnotationKeyword
 */
trait AnnotationKeywordMethodAnnotations {}
