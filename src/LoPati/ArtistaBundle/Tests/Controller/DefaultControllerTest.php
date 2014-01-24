<?php

namespace LoPati\ArtistaBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIrradiadorCa()
    {
        $client = static::createClient();
        $client->request('GET', '/ca/projectes/irradiador/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    // TODO: test artista detail
    public function testIrradiadorDetail()
    {
        /*$client = static::createClient();
        $client->request('GET', '/ca/projectes/irradiador/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());*/
    }
}
