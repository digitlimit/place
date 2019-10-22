<?php

namespace Digitlimit\Place\Contracts;

use Illuminate\Support\Collection;

interface Place
{
    public function textSearch($query=null) : object;

    public function nearBySearch($query=null) : object;

    public function toJson() : string;

    public function toArray() : array;

    public function toCollection() : Collection;
}