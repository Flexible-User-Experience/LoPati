<?php

namespace LoPati\BlogBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class DefaultControllerTest
 */
class DefaultControllerTest extends WebTestCase
{
    /**
     * Test homepage
     */
    public function testInici()
    {
        $client = static::createClient();
        $client->request('GET', '/');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }

    /**
     * Test locale homepage
     */
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

    /**
     * Test categories
     */
    public function testCategoriaEnllas()
    {
        $client = static::createClient();
        $client->request('GET', '/ca/projectes/1/');
        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    // TODO pending archive test
//    public function testArxiuArticle()
//    {
//        $client = static::createClient();
//        $client->request('GET', '/ca/1/arxiu/4/');
//        $this->assertTrue($client->getResponse()->isSuccessful());
//    }
}
