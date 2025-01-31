<?php

namespace BasilLangevin\LaravelDataSchemas;

use BasilLangevin\LaravelDataSchemas\Support\DataClassSchemaRegistry;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelDataSchemasServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-data-schemas')
            ->hasConfigFile();
    }

    public function packageBooted()
    {
        $this->app->scoped(DataClassSchemaRegistry::class);
    }
}
