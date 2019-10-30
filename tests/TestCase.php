<?php

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tests\CreatesApplication;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * @param $uri
     * @param array $data
     * @param string $jwt_token
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function ajaxGet($uri, array $data = [], $jwt_token='')
    {
        $headers = [
            'Accept' => 'application/json',
        ];

        if($jwt_token){
            $headers['Authorization'] = 'Bearer ' . $jwt_token;
        }

        return $this->withHeaders($headers)
            ->json('GET', $uri, $data);
    }

}
