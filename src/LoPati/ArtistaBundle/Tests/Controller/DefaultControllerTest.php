<?php

namespace LoPati\ArtistaBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class DefaultControllerTest
 */
class DefaultControllerTest extends WebTestCase
{
    /**
     * Test HTTP response is successful.
     */
    public function testIrradiador()
    {
        $client = static::createClient();
        $client->request('GET', '/ca/projectes/irradiador/');
        $this->assertTrue($client->getResponse()->isSuccessful());
        $client->request('GET', '/es/proyectos/irradiador/');
        $this->assertTrue($client->getResponse()->isSuccessful());
        $client->request('GET', '/en/projects/irradiador/');
        $this->assertTrue($client->getResponse()->isSuccessful());
        $client->request('GET', '/en/proyects/irradiador/');
        $this->assertTrue($client->getResponse()->isNotFound());
    }

    /**
     * Test HTTP response is successful.
     */
    public function testIrradiadorDetail()
    {
        $client = static::createClient();
        $client->request('GET', '/ca/projectes/irradiador/alba-sotorra/');
        $this->assertTrue($client->getResponse()->isSuccessful());
        $client->request('GET', '/ca/projectes/irradiador/alba-sotorra-666/');
        $this->assertTrue($client->getResponse()->isServerError());
    }
}
