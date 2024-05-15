<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EvenementControllerTest extends WebTestCase
{
    public function testGetAllEvenements()
    {
        $client = static::createClient();
        $client->request('GET', '/evenements/getall');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
    }

    public function testGetOneEvenement()
    {
        $client = static::createClient();
        $client->request('GET', '/evenements/getone/1');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
    }

    public function testAddEvenement()
    {
        $client = static::createClient();
        $client->request('POST', '/evenements/add', [], [], [], json_encode([
            'nom' => 'Test Event',
            'description' => 'Test Description',
            'lieu' => 'Test Location',
            'type' => 1,
            'age_requis' => 18,
            'image' => 'Test Image',
            'dates' => [
                ['date' => '2022-12-31', 'places_rest' => 100],
                ['date' => '2023-01-01', 'places_rest' => 200]
            ]
        ]));
//TODO check siles dates sont egalemnt créer dans la bdd
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
    }

    public function testDeleteEvenement()
    {
        //TODO: creer un event avant
        $client = static::createClient();
        $client->request('DELETE', '/evenements/delete/10');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
    }

    public function testCancelEvent()
    {
        $client = static::createClient();
        $client->request('PUT', '/evenements/annule/1', [], [], [], json_encode([
            'raison_annulation' => 'Test Reason'
        ]));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
    }
}