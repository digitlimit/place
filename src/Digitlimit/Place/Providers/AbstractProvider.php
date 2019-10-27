<?php

namespace Digitlimit\Place\Providers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

abstract class AbstractProvider
{
    /**
     * Complete API response
     * @var
     */
    protected $response;

    /**
     * Search results
     *
     * @var array
     */
    protected $results = [];

    /**
     * The HTTP request instance.
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * The HTTP Client instance.
     *
     * @var \GuzzleHttp\Client
     */
    protected $httpClient;

    /**
     * The query string.
     *
     * @var string
     */
    protected $query;

    /**
     * Response output type
     * Output may be either of the following values:
     * json (recommended) indicates output in JavaScript Object Notation (JSON)
     * xml indicates output as XML
     *
     * @var string
     */
    protected $output = 'json';

    /**
     * The client ID.
     *
     * @var string
     */
    protected $client_id;

    /**
     * The custom parameters to be sent with the request.
     *
     * @var array
     */
    protected $parameters = [];

    /**
     * The custom Guzzle configuration options.
     *
     * @var array
     */
    protected $guzzle_options = [];

    /**
     * Create a new provider instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $client_id
     * @param  array  $guzzle
     * @return void
     */
    public function __construct(Request $request, $client_id, $guzzle = [])
    {
        $this->guzzle_options = $guzzle;
        $this->request = $request;
        $this->client_id = $client_id;
    }

    /**
     * Get Base Url.
     *
     * @return string
     */
    abstract protected function getBaseUrl() : string;

    /**
     * Get Full Url.
     *
     * @return string
     */
    abstract protected function getFullUrl() : string;

    /**
     * Get the query from the request.
     *
     * @return string
     */
    protected function getQuery() : string
    {
        $query = $this->query ? $this->query :
            $this->request->input('query');

        return $query ? $query : '';
    }

    /**
     * Get a instance of the Guzzle HTTP client.
     *
     * @return \GuzzleHttp\Client
     */
    protected function getHttpClient()
    {
        if (is_null($this->httpClient)) {
            $this->httpClient = new Client($this->guzzle_options);
        }

        return $this->httpClient;
    }

    /**
     * Set the Guzzle HTTP client instance.
     *
     * @param  \GuzzleHttp\Client  $client
     * @return $this
     */
    public function setHttpClient(Client $client)
    {
        $this->httpClient = $client;

        return $this;
    }

    /**
     * Set the request instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return $this
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;

        return $this;
    }

    /**
     * Set the custom parameters of the request.
     *
     * @param  array  $parameters
     * @return $this
     */
    public function with(array $parameters)
    {
        $this->parameters = array_merge($this->parameters, $parameters);

        return $this;
    }

    public function responseHas($parameter) : bool {

        if(is_array($this->response) && isset($this->response[$parameter])){
            return true;
        }

        return false;
    }

    public function getResponse($parameter=null){

        if(is_array($this->response) && isset($this->response[$parameter])){
            return $this->response[$parameter];
        }

        return $this->response;
    }

    protected function buildQuery(array $parameters)
    {
        if($parameters){
            $this->with($parameters);
        }

        return $this->parameters;
    }
}