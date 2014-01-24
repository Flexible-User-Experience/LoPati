<?php

namespace LoPati\NewsletterBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $client->request('POST', '/newsletter/suscribe');

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }
}
