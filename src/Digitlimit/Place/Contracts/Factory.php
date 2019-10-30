<?php

namespace Digitlimit\Place\Contracts;

interface Factory
{
    /**
     * Provider driver
     *
     * @param null $driver
     * @return mixed
     */
    public function driver($driver = null);
}
