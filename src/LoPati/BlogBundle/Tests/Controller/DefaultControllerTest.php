<?php

namespace LoPati\BlogBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testInici()
    {
        $client = static::createClient();
        $client->request('GET', '/');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }

    public function testPortada()
    {
        $client = static::createClient();
        $client->request('GET', '/ca/');
        $this->assertTrue($client->getResponse()->isSuccessful());
        $client->request('GET', '/es/');
        $this->assertTrue($client->getResponse()->isSuccessful());
        $client->request('GET', '/en/');
        $this->assertTrue($client->getResponse()->isSuccessful());
        $client->request('GET', '/xx/');
        $this->assertTrue($client->getResponse()->isNotFound());
    }

    public function testCategoriaEnllas()
    {
        $client = static::createClient();
        $client->request('GET', '/ca/projectes/18');
        $this->assertEquals(301, $client->getResponse()->getStatusCode());
    }
}
