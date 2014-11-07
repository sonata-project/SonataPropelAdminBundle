<?php

namespace Sonata\PropelAdminBundle\Tests\Functionnal;

class DashboardTest extends WebTestCase
{
    public function testAccessible()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/admin/dashboard');

        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertCount(1, $crawler->filter('div.sonata-ba-content:contains("Blog")'), 'There is a "Blog" section');
        $this->assertCount(1, $crawler->filter('div.sonata-ba-content:contains("Posts")'), 'There is a "Posts" sub-section');
    }
}
