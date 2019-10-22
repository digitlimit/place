<?php
namespace Digitlimit\Place\Providers;

use Digitlimit\Place\Contracts\Place as PlaceContract;
use Illuminate\Support\Collection;

class Google extends AbstractProvider implements PlaceContract
{
    protected $results = [];

    protected $status;

    public function textSearch($query=null) : object{
        $this->query = $query;
        return $this;
    }

    public function nearBySearch($query=null) : object{
        $this->query = $query;
        return $this;
    }

    public function toJson() : string{

        $place = $this->getPlace();

        return json_encode($place);
    }

    public function toArray() : array{
        return $this->getPlace();
    }

    public function toCollection() : Collection{
        $place = $this->getPlace();

        return collect($place);
    }



    protected function getPlace()
    {
        if(isset($this->parameters['next_page_token'])){
            $this->response = $this->getPlaceByToken($this->parameters['next_page_token']);
        }else{
            $this->response = $this->getPlaceByQuery();
        }

        //on successful request a result is returned
        if(is_array($this->response) && isset($this->response['results'])){
            return $this->response['results'];
        }

        //TODO: handle different errors
        dd($this->response);
    }

    protected function getBaseUrl($path=null) : string
    {
        $url = "https://maps.googleapis.com/maps/api/place";
        return $path ? $url . "/" . $path : $url;
    }

    protected function getFullUrl() : string
    {
        return $this->getBaseUrl("textsearch/{$this->output}");
    }

    protected function getPlaceByQuery() : array
    {
        $response = $this->getHttpClient()->get($this->getFullUrl(), [
            'query' => [
                'query' => $this->getQuery(),
                'key' => $this->client_id,
            ]
        ]);

        return (array) json_decode($response->getBody(), true);
    }

    protected function getPlaceByToken($token)
    {
        $url = $this->resolveNextPageUrl($token);

        $response = $this->getHttpClient()->get($url);

        return (array) json_decode($response->getBody(), true);
    }

    protected function getPlaceByUrl($url, $callback=null)
    {
        $response = $this->getHttpClient()
            ->get($url);

        return (array) json_decode($response->getBody(), true);
    }

    protected function resolveNextPageUrl($token)
    {
        return  $next_page_token_url = $this->getFullUrl()
            . "?pagetoken={$token}"
            . "&key={$this->client_id}";
    }
}