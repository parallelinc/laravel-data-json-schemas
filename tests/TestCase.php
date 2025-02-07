<?php

namespace BasilLangevin\LaravelDataJsonSchemas\Tests;

use BasilLangevin\LaravelDataJsonSchemas\LaravelDataJsonSchemasServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\LaravelData\LaravelDataServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelDataJsonSchemasServiceProvider::class,
            LaravelDataServiceProvider::class,
        ];
    }
}
