<?php

namespace LoPati\BlogBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testHomepage()
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }

    public function testHomepageCa()
    {
        $client = static::createClient();
        $client->request('GET', '/ca/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
