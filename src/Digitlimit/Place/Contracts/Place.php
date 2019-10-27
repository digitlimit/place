<?php

namespace Digitlimit\Place\Contracts;

use Illuminate\Support\Collection;

interface Place
{
    /**
     * Perform places text search
     *
     * @param null $query
     * @return Place
     */
    public function textSearch($query=null) : Place;

    /**
     * Perform places NearBy Search
     *
     * @param null $query
     * @return Place
     */
    public function nearBySearch($query=null) : Place;

    /**
     * Fetch Next page from results
     *
     * @param array $parameters
     * @return Place
     */
    public function getNextPage(array $parameters=[]) : Place;

    /**
     * Check if there is next page
     *
     * @return bool
     */
    public function hasNextPage() : bool;

    /**
     * Return JSON result set
     * @return string
     */
    public function toJson() : string;

    /**
     * Return Array result set
     * @return array
     */
    public function toArray() : array;

    /**
     * Return Collection result set
     * @return Collection
     */
    public function toCollection() : Collection;
}