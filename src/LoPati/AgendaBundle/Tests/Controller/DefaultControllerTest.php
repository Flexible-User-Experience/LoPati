<?php

namespace LoPati\AgendaBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

/**
 * Class DefaultControllerTest
 */
class DefaultControllerTest extends WebTestCase
{
    /**
     * Test HTTP response is successful.
     */
    public function testIndex()
    {
        $client = $this->createClient();           // anonymous user

        $client->request('GET', '/ca/agenda/2018/01/01/');
        $this->assertStatusCode(200, $client);
    }
}
