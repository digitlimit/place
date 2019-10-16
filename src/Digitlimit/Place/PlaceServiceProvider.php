<?php

namespace Digitlimit\Place;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class PlaceServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('digitlimit.place', function ($app) {
            return new Place();
        });
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {

    }
}