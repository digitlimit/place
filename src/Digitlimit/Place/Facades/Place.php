<?php

namespace Digitlimit\Place\Facades;
use Illuminate\Support\Facades\Facade;
use Digitlimit\Place\Contracts\Factory;

class Place extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return Factory::class;
    }
}