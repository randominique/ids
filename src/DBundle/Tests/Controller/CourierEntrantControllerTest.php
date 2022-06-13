<?php

namespace DBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CourierEntrantControllerTest extends WebTestCase
{
    public function testNew()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', 'nouveau');
    }

    public function testList()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', 'liste');
    }

}
