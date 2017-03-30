<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/admin');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $crawler = $client->request('GET', '/active');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }
}
