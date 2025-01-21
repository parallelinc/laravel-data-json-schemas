<?php

namespace BasilLangevin\LaravelDataSchemas\Schemas\Concerns;

use BasilLangevin\LaravelDataSchemas\Schemas\Contracts\Schema;

trait ConstructsSchema
{
    public function __construct(
        protected string $name = '',
        string $description = '',
    ) {
        if ($description) {
            $this->description($description);
        }
    }

    public function name(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public static function make(string $name = '', string $description = ''): Schema
    {
        return new static($name, $description);
    }
}
