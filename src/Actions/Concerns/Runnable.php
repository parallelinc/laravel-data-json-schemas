<?php

namespace BasilLangevin\LaravelDataSchemas\Actions\Concerns;

/**
 * @template TArguments
 * @template TReturn
 */
trait Runnable
{
    /**
     * Run the action.
     *
     * @param  TArguments  ...$arguments  The arguments that will be forwarded to the handle method
     * @return TReturn The return value of the handle method
     */
    public static function run(...$arguments): mixed
    {
        /** @var static $instance */
        $instance = app(static::class);

        /** @var TReturn */
        return call_user_func_array([$instance, 'handle'], $arguments);
    }
}
