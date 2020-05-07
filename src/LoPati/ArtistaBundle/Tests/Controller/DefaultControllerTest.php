<?php

namespace LoPati\ArtistaBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

/**
 * Class DefaultControllerTest
 */
class DefaultControllerTest extends WebTestCase
{
    /**
     * Test HTTP response is successful.
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
            array('/ca/projectes/irradiador/'),
            array('/es/proyectos/irradiador/'),
            array('/ca/projectes/irradiador/alba-sotorra/'),
        );
    }

    /**
     * Other tests
     */
    public function otherTests()
    {
        $client = $this->createClient();

        $client->request('GET', '/en/proyects/irradiador/');
        $this->assertStatusCode(404, $client);
        $client->request('GET', '/ca/projectes/irradiador/alba-sotorra-666/');
        $this->assertStatusCode(500, $client);
    }
}
