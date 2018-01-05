<?php

namespace Louvre\TicketingBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use PHPUnit\Framework\TestCase;

class DefaultControllerTest extends TestCase
{

    private $client = null;

    public function setUp()
    {
        $this->client = static::createClient();
    }


    public function essaiIndex()
    {
        $this->client->request('GET', '/fr/');

        static::assertEquals(
            Response::HTTP_OK,
            $this->client->getResponse()->getStatusCode()
        );



//        $client = static::createClient();
//
//        $crawler = $client->request('GET', '/');
//
//        $this->assertContains('Hello World', $client->getResponse()->getContent());
    }
}
