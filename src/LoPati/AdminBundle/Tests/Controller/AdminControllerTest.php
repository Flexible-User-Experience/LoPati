<?php

namespace LoPati\AdminBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class AdminControllerTest extends WebTestCase
{
    /**
     * Test admin login request is successful
     */
    public function testAdminLoginPageIsSuccessful()
    {
        $client = $this->createClient();           // anonymous user
        $client->request('GET', '/admin/login');

        $this->assertStatusCode(200, $client);
    }

    /**
     * Test page is successful
     *
     * @dataProvider provideUrls
     *
     * @param $url
     */
    public function testAdminPagesAreSuccessful($url)
    {
        $client = $this->makeClient(true);         // authenticated user
        $client->request('GET', $url);

        $this->assertStatusCode(200, $client);
    }

    /**
     * Urls provider
     *
     * @return array
     */
    public function provideUrls()
    {
        return array(
            array('/admin/dashboard'),
            array('/admin/menu/level/1/list'),
            array('/admin/menu/level/1/create'),
            array('/admin/menu/level/1/1/edit'),
            array('/admin/menu/level/2/list'),
            array('/admin/menu/level/2/create'),
            array('/admin/menu/level/2/1/edit'),
            array('/admin/page/list'),
            array('/admin/page/create'),
            array('/admin/page/1/edit'),
            array('/admin/page/1/delete'),
            array('/admin/page/1/duplicate'),
            array('/admin/archive/list'),
            array('/admin/archive/create'),
            array('/admin/archive/1/edit'),
            array('/admin/configuration/footer/list'),
            array('/admin/configuration/footer/1/edit'),
            array('/admin/configuration/calendar/list'),
            array('/admin/newsletter/group/list'),
            array('/admin/newsletter/group/create'),
            array('/admin/newsletter/group/1/edit'),
            array('/admin/newsletter/user/list'),
            array('/admin/newsletter/user/create'),
            array('/admin/newsletter/user/1/edit'),
            array('/admin/newsletter/list'),
            array('/admin/newsletter/create'),
            array('/admin/newsletter/1/edit'),
            array('/admin/newsletter/1/preview'),
            array('/admin/artist/list'),
            array('/admin/artist/create'),
            array('/admin/artist/1/edit'),
            array('/admin/slider/list'),
            array('/admin/slider/create'),
            array('/admin/slider/1/edit'),
        );
    }
}
