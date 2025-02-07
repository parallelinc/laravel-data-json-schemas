<?php

namespace BasilLangevin\LaravelDataJsonSchemas;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelDataJsonSchemasServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-data-json-schemas')
            ->hasConfigFile('json-schemas');
    }
}
