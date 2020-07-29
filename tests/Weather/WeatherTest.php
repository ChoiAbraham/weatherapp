<?php

namespace App\Tests\Weather;

use App\Tests\AbstractWebTestCase;

class WeatherTest extends AbstractWebTestCase
{
    public function testGetHttpGuzzleRequest()
    {
        $apiKey = $this->containerService->getParameter('api_parameter');

        $client = $this->containerService->get('csa_guzzle.client.weather');
        $request = $client->get('/data/2.5/weather?q=paris&APPID=' . $apiKey);
        $data = json_decode($request->getBody()->getContents(), true);
        $this->assertArrayHasKey('coord', $data);
    }
}