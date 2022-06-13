<?php

namespace DBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CourierSortantControllerTest extends WebTestCase
{
    public function testNew()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', 'couriers/sortant/nouveau');
    }

    public function testList()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', 'couriers/sortant/liste');
    }

}
