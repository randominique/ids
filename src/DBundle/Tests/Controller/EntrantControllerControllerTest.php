<?php

namespace DBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EntrantControllerControllerTest extends WebTestCase
{
    public function testNew()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', 'courrier/entrant/new');
    }

    public function testList()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', 'courrier/entrant/liste');
    }

    public function testShow()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', 'DBundle:Entrant:show.html.twig');
    }

}
