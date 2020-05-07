<?php

namespace LoPati\BlogBundle\Tests\Controller;

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
    public function testSuccess($url)
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
            array('/ca/'),
            array('/es/'),
            array('/newsletter/1/'),
            array('/ca/search/'),
            array('/ca/projectes/1/'),
            array('/ca/1/arxiu/4/'),
            array('/ca/politica-de-privacitat/'),
            array('/es/politica-de-privacidad/'),
        );
    }

    /**
     * Other tests
     */
    public function otherTests()
    {
        $client = $this->createClient();

        $client->request('GET', '/');
        $this->assertStatusCode(302, $client);
        $client->request('GET', '/xx/');
        $this->assertStatusCode(404, $client);
        $client->request('GET', '/newsletter/250/');
        $this->assertStatusCode(404, $client);
    }
}
