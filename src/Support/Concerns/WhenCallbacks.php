<?php

namespace BasilLangevin\LaravelDataSchemas\Support\Concerns;

use Closure;

trait WhenCallbacks
{
    public function when(bool $condition, Closure $callback): static
    {
        if ($condition) {
            $callback($this);
        }

        return $this;
    }
}
