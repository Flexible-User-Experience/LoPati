<?php

namespace LoPati\BlogBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminControllerTest extends WebTestCase
{
    public function testHomepage()
    {
        $client = static::createClient();
        $client->request('GET', '/admin/login');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
