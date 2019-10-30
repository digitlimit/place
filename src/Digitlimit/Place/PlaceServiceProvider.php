<?php

namespace Digitlimit\Place;
use Illuminate\Support\ServiceProvider;
use Digitlimit\Place\Contracts\Factory;

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
        $this->app->singleton(Factory::class, function ($app) {
            return new PlaceManager($app);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [Factory::class];
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