<?php

namespace LoPati\AdminBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminControllerTest extends WebTestCase
{
    public function testHomepage()
    {
        $client = static::createClient();
        $client->request('GET', '/admin/login');
        $this->assertTrue($client->getResponse()->isSuccessful());
    }
}
