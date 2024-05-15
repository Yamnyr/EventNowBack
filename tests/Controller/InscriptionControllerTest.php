<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class InscriptionControllerTest extends WebTestCase
{
    public function testGetInscription()
    {
        $client = static::createClient();
        $client->request('GET', 'http://127.0.0.1:8000/inscriptions/getone/1');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testAddInscription()
    {
        $client = static::createClient();
        $client->request('POST', '/inscriptions/add', [], [], [], json_encode([
            'user_id' => 1,
            'date_id' => 1,
            'certif' => true,
            'nombre_pers' => 3
        ]));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testAddInscriptionError()
    {
        $client = static::createClient();
        $client->request('POST', '/inscriptions/add', [], [], [], json_encode([
            'user_id' => 1,
            'date_id' => 1,
            'certif' => false,
            'nombre_pers' => 3
        ]));

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }


    public function testGetUserInscriptions()
    {
        $client = static::createClient();
        $client->request('GET', '/inscriptions/user/1/inscriptions');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}