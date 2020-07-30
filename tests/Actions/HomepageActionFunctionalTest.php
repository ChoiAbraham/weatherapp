<?php

namespace App\Tests\Actions;

use App\Tests\AbstractWebTestCase;
use Symfony\Component\HttpFoundation\Response;

class HomepageActionFunctionalTest extends AbstractWebTestCase
{
    public function testHomepageIsUp()
    {
        $this->client->request('GET', '/');

        static::assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testErrorNoCityFound()
    {
        $crawler = $this->client->request('GET', '/');

        $form = $crawler->selectButton('Soumettre')->form();

//        dd($form);
        $this->client->submit($form, [
            'city_weather[city]' => 'blabla'
        ]);
        static::assertTrue($this->client->getResponse()->isRedirection());
        $crawler = $this->client->followRedirect();
        static::assertTrue($this->client->getResponse()->isSuccessful());
    }

    public function testSuccessNewCity()
    {
        $crawler = $this->client->request('GET', '/');

        $form = $crawler->selectButton('Soumettre')->form();

        $this->client->submit($form, [
            'city_weather[city]' => 'paris'
        ]);

        static::assertTrue($this->client->getResponse()->isSuccessful());
    }
}
