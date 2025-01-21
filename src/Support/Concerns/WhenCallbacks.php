<?php

namespace BasilLangevin\LaravelDataSchemas\Support\Concerns;

trait WhenCallbacks
{
    public function when(bool $condition, callable $callback): self
    {
        if ($condition) {
            $callback($this);
        }

        return $this;
    }
}
