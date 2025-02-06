<?php

namespace BasilLangevin\LaravelDataSchemas\Attributes;

use Attribute;
use BasilLangevin\LaravelDataSchemas\Attributes\Contracts\ArrayAttribute;

/**
 * Adds a custom annotation to a Data object or property.
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

    public function getValue(): array
    {
        if (is_array($this->annotation)) {
            return $this->annotation;
        }

        /** @var array<string, string> */
        return [$this->annotation => $this->value];
    }
}
