<?php

namespace BasilLangevin\LaravelDataJsonSchemas\Attributes;

use Attribute;
use BasilLangevin\LaravelDataJsonSchemas\Attributes\Contracts\ArrayAttribute;

/**
 * Adds custom annotation(s) to a Data object or property.
 *
 * The annotation can be created with an array of key-value pairs
 * or a string key and value.
 */
#[Attribute(Attribute::IS_REPEATABLE | Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY)]
class CustomAnnotation implements ArrayAttribute
{
    /**
     * @param  string|array<string, string>  $annotation
     */
    public function __construct(protected string|array $annotation, protected ?string $value = null)
    {
        if (is_string($annotation) && is_null($value)) {
            throw new \InvalidArgumentException('Custom annotations require a key and value.');
        }
    }

    /**
     * Get the annotation(s) as an array of key-value pairs.
     *
     * @return array<string, string>
     */
    public function getValue(): array
    {
        if (is_array($this->annotation)) {
            return $this->annotation;
        }

        /** @var array<string, string> */
        return [$this->annotation => $this->value];
    }
}
