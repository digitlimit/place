<?php
namespace Digitlimit\Place\Providers;

use Digitlimit\Place\Contracts\Place as PlaceContract;
use Illuminate\Support\Collection;

class Google extends AbstractProvider implements PlaceContract
{
    /**
     * Determine if are requesting next page
     *
     * @var bool
     */
    protected $next_page = false;

    /**
     * Perform places text search
     *
     * @param null $query
     * @return PlaceContract
     */
    public function textSearch($query=null) : PlaceContract{
        $this->query = $query;
        return $this;
    }

    /**
     * Perform places NearBy Search
     *
     * @param null $query
     * @return PlaceContract
     */
    public function nearBySearch($query=null) : PlaceContract{
        $this->query = $query;
        return $this;
    }

    /**
     * Fetch Next page from results
     *
     * @param array $parameters
     * @return PlaceContract
     */
    public function getNextPage(array $parameters=[]) : PlaceContract
    {
        //TODO set limit in config file
        $this->next_page = true;

        if($parameters){
            $this->with($parameters);
        }

        return $this;
    }

    /**
     * Check if there is next page
     *
     * @return bool
     */
    public function hasNextPage() : bool
    {
        if(!$this->response || !is_array($this->response)){
            return false;
        }

        if(!isset($this->response['next_page_token'])){
            return false;
        }

        return $this->response['next_page_token'] ? true : false;
    }

    /**
     * Return JSON result set
     * @return string
     */
    public function toJson() : string{
        $place = $this->getPlace();
        return json_encode($place);
    }

    /**
     * Return Array result set
     * @return array
     */
    public function toArray() : array{
        return $this->getPlace();
    }

    /**
     * Return Collection result set
     * @return Collection
     */
    public function toCollection() : Collection{
        $place = $this->getPlace();
        return collect($place);
    }

    /**
     * Make request to place API
     *
     * @return array
     */
    protected function getPlace()
    {
        $this->response = $this->getPlaceByQuery();

        //on successful request a result is returned
        if(is_array($this->response) && isset($this->response['results'])){
            return $this->transformToPlaceFormat($this->response['results']);
        }

        //TODO: handle different errors
        info($this->response);
    }

    /**
     * Transform response to Place format
     *
     * @param array $results
     * @return array
     */
    protected function transformToPlaceFormat(array $results) : array
    {
        $transformed_results = [];

        foreach($results as $result)
        {
            $transformed_results[] = [
                'name' => $result['name'],
                'brands' => [],
                'website' => '',
                'formatted_address' => $result['formatted_address'],
                'lat' => $result['geometry']['location']['lat'],
                'lng' => $result['geometry']['location']['lng'],
                'icon' => $result['icon'],
                'opening_hours' => '',
                'photos' => isset($result['photos']) ? $result['photos'] : [],
                'rating' => isset($result['rating']) ? $result['rating'] : '',
                'user_ratings_total' => isset($result['user_ratings_total']) ? $result['user_ratings_total'] :[],
                'types' => isset($result['types']) ? $result['types'] : [] //e.g gas station
            ];
        }

        return $transformed_results;
    }

    /**
     * Get API Base Url
     *
     * @param null $path
     * @return string
     */
    protected function getBaseUrl($path=null) : string
    {
        $url = "https://maps.googleapis.com/maps/api/place";
        return $path ? $url . "/" . $path : $url;
    }

    /**
     * Get Full API Url
     *
     * @return string
     */
    protected function getFullUrl() : string
    {
        return $this->getBaseUrl("textsearch/{$this->output}");
    }

    /**
     * Build Query
     * @return array
     */
    protected function getPlaceByQuery() : array
    {
        if($this->next_page && $this->hasNextPage())
        {
            $url = $this->getFullUrl()
                . "?pagetoken={$this->response['next_page_token']}"
                . "&key={$this->client_id}";

            $response = $this->getHttpClient()->get($url);

        }else{
            $response = $this->getHttpClient()->get($this->getFullUrl(), [
                'query' => $this->buildQuery([
                    'query' => $this->getQuery(),
                    'key' => $this->client_id
                ])
            ]);
        }

        return (array) json_decode($response->getBody(), true);
    }
}