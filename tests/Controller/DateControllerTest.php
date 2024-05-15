<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DateControllerTest extends WebTestCase
{
    public function testGetAllDates()
    {
        $client = static::createClient();
        $client->request('GET', '/dates/getall');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testGetDate()
    {
        $client = static::createClient();
        $client->request('GET', '/dates/getone/1');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testGetDateERROR()
    {
        $client = static::createClient();
        $client->request('GET', '/dates/getone/1000');

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }
}