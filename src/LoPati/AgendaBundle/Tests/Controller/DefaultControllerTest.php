<?php

namespace LoPati\AgendaBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

/**
 * Class DefaultControllerTest
 */
class DefaultControllerTest extends WebTestCase
{
    /**
     * Test page is successful.
     *
     * @dataProvider provideSuccessUrls
     *
     * @param $url
     */
    public function testIndex($url)
    {
        $client = $this->createClient();

        $client->request('GET', $url);
        $this->assertStatusCode(200, $client);
    }

    /**
     * Success urls provider.
     *
     * @return array
     */
    public function provideSuccessUrls()
    {
        return array(
            array('/ca/agenda/2018/01/01/'),
            array('/es/agenda/2018/01/02/'),
            array('/en/diary/2018/01/03/'),
        );
    }

    /**
     * Other tests
     */
    public function otherTests()
    {
        $client = $this->createClient();

        $client->request('GET', '/ca/calendari/left');
        $this->assertStatusCode(404, $client);
        $client->request('GET', '/ca/calendari/dreta');
        $this->assertStatusCode(301, $client);
        $client->request('GET', '/es/calendari/esquerra');
        $this->assertStatusCode(301, $client);
    }
}
