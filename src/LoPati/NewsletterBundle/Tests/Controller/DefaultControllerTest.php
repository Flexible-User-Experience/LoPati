<?php

namespace LoPati\NewsletterBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

/**
 * Class DefaultControllerTest
 */
class DefaultControllerTest extends WebTestCase
{
    /**
     * Test newsletter subscribe
     */
    public function testIndex()
    {
        $client = $this->createClient();

        $client->request('POST', '/newsletter/suscribe');
        $this->assertStatusCode(302, $client);
    }
}
