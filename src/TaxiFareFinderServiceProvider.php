<?php

namespace TomOehlrich\TaxiFareFinder;

use Illuminate\Support\ServiceProvider;


class TaxiFareFinderServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/taxifarefinder.php' => config_path('taxifarefinder.php'),
        ], 'config');
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/taxifarefinder.php', 'taxifarefinder');

        $this->app->bind('taxifarefinder', function ($app) {
            return (new TaxiFareFinder(config('taxifarefinder.api_key')));
        });
    }


}
