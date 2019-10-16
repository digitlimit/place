<?php

namespace Digitlimit\Place\Facades;
use Illuminate\Support\Facades\Facade;

class Place extends Facade
{
    /**
     * Get the binding in the IoC container.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'digitlimit.place';
    }
}