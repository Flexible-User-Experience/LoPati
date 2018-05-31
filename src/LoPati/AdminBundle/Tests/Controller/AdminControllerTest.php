<?php

namespace LoPati\AdminBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

/**
 * Class AdminControllerTest
 */
class AdminControllerTest extends WebTestCase
{
    /**
     * Test admin login request is successful.
     */
    public function testAdminLoginPageIsSuccessful()
    {
        $client = $this->createClient();           // anonymous user

        $client->request('GET', '/admin/login');
        $this->assertStatusCode(200, $client);
    }

    /**
     * Test page is successful.
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
     * Urls provider.
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
            array('/admin/pages/page/list'),
            array('/admin/pages/page/create'),
            array('/admin/pages/page/1/edit'),
            array('/admin/pages/page/1/delete'),
            array('/admin/pages/newsletter/list'),
            array('/admin/pages/newsletter/create'),
            array('/admin/pages/newsletter/1/edit'),
            array('/admin/pages/newsletter/1/preview'),
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
            array('/admin/newsletter/newsletter/list'),
            array('/admin/newsletter/newsletter/create'),
            array('/admin/newsletter/newsletter/1/edit'),
            array('/admin/newsletter/newsletter/1/delete'),
            array('/admin/newsletter/newsletter/1/preview'),
            array('/admin/newsletter/newsletter-post/list'),
            array('/admin/newsletter/newsletter-post/create'),
            array('/admin/newsletter/newsletter-post/1/edit'),
            array('/admin/artist/list'),
            array('/admin/artist/create'),
            array('/admin/artist/1/edit'),
            array('/admin/slider/list'),
            array('/admin/slider/create'),
            array('/admin/slider/1/edit'),
            array('/admin/slider/1/delete'),
            array('/admin/configuration/footer/list'),
            array('/admin/configuration/footer/1/edit'),
            array('/admin/configuration/calendar/list'),
        );
    }
}
