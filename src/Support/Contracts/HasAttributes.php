<?php

namespace BasilLangevin\LaravelDataJsonSchemas\Support\Contracts;

use BasilLangevin\LaravelDataJsonSchemas\Support\AttributeWrapper;
use Illuminate\Support\Collection;

interface HasAttributes
{
    /**
     * Get the attributes of the property as a collection of AttributeWrappers.
     *
     * @return Collection<int, AttributeWrapper>
     */
    public function attributes(?string $name = null): Collection;

    /**
     * Check if the property has the given attribute.
     */
    public function hasAttribute(string $name): bool;

    /**
     * Get the attribute(s) of the property as AttributeWrapper(s).
     *
     * @return AttributeWrapper|Collection<int, AttributeWrapper>|null
     */
    public function getAttribute(string $name): AttributeWrapper|Collection|null;
}
