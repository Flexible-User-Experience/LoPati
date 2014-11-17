<?php

namespace LoPati\AdminBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;

class AdminControllerTest extends WebTestCase
{
    /**
     * Test page is successful
     *
     * @dataProvider provideUrls
     */
    public function testAdminPagesAreSuccessful($url)
    {
        $client = $this->getAdminClient();
        $client->request('GET', $url);
        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    /**
     * Get admin client
     *
     * @return Client
     */
    private function getAdminClient()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/admin/login');
        $form = $crawler->selectButton('_submit')->form(
            array(
                '_username' => static::$kernel->getContainer()->getParameter('admin_user_test'),
                '_password' => static::$kernel->getContainer()->getParameter('admin_pass_test'),
            )
        );
        $client->submit($form);

        return $client;
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
            array('/admin/page/2/edit'),
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
            array('/admin/newsletter/user/5/edit'),
            array('/admin/newsletter/list'),
            array('/admin/newsletter/create'),
            array('/admin/newsletter/7/edit'),
            array('/admin/newsletter/7/preview'),
            array('/admin/artist/list'),
            array('/admin/artist/create'),
            array('/admin/artist/1/edit'),
            array('/admin/slider/list'),
            array('/admin/slider/create'),
            array('/admin/slider/1/edit'),
            array('/admin/slider/1/delete'),
        );
    }
}
