<?php

namespace Digitlimit\Place;

use Illuminate\Support\Arr;
use InvalidArgumentException;
use Illuminate\Support\Manager;
use Digitlimit\Place\Providers\Google;
use Digitlimit\Place\Providers\AbstractProvider;

class PlaceManager extends Manager implements Contracts\Factory
{
    /**
     * Get a driver instance.
     *
     * @param  string  $driver
     * @return mixed
     */
    public function with($driver)
    {
        return $this->driver($driver);
    }

    /**
     * Create an instance of the specified driver.
     *
     * @return \Digitlimit\Place\Providers\AbstractProvider
     */
    protected function createGoogleDriver() : AbstractProvider
    {
        $config = $config = $this->getDriverConfig('google');

        return $this->buildProvider(
            Google::class, $config
        );
    }

    /**
     * Build provider instance.
     *
     * @param  string  $provider
     * @param  array  $config
     * @return \Digitlimit\Place\Providers\AbstractProvider
     */
    public function buildProvider($provider, $config)
    {
        return new $provider(
            $this->app['request'],
            $config['client_id'],
            Arr::get($config, 'guzzle', [])
        );
    }

    /**
     * Get the default driver name.
     *
     * @throws \InvalidArgumentException
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        $config = $this->app['config']['place'];

        if(is_array($config) && isset($config['defaults']) && $config['defaults']['driver']){
            return $config['defaults']['driver'];
        }

        throw new InvalidArgumentException('No Place driver as specified or configured');
    }

    /**
     * Get the place config
     *
     * @param $driver
     * @return mixed
     */
    protected function getDriverConfig($driver){

        $config = $this->app['config']['place'];

        if(is_array($config) && isset($config['providers']) && $config['providers'][$driver]){
            return $config['providers'][$driver];
        }

        throw new InvalidArgumentException('No driver found for '. $driver);
    }
}
