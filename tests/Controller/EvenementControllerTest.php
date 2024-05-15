<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EvenementControllerTest extends WebTestCase
{
    /**
     * Ce test vérifie que l'endpoint 'GET /evenements/getall' fonctionne correctement.
     * On s'attend à une réponse réussie (HTTP 200) lorsqu'on demande tous les événements.
     */
    public function testGetAllEvenements()
    {
        $client = static::createClient();
        $client->request('GET', '/evenements/getall');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
    }

    /**
     * Ce test vérifie que l'endpoint 'GET /evenements/getone/{id}' fonctionne correctement avec un ID valide.
     * On s'attend à une réponse réussie (HTTP 200) lorsqu'on demande un événement avec un ID valide.
     */
    public function testGetOneEvenement()
    {
        $client = static::createClient();
        $client->request('GET', '/evenements/getone/1');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
    }

    /**
     * Ce test vérifie que l'endpoint 'POST /evenements/add' fonctionne correctement.
     * On s'attend à une réponse réussie (HTTP 200) lorsqu'on ajoute un nouvel événement.
     * TODO: Vérifier si les dates sont également créées dans la base de données.
     */
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

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
    }

    /**
     * Ce test vérifie que l'endpoint 'DELETE /evenements/delete/{id}' fonctionne correctement.
     * On s'attend à une réponse réussie (HTTP 200) lorsqu'on supprime un événement.
     * TODO: Créer un événement avant de le supprimer.
     */
    public function testDeleteEvenement()
    {
        $client = static::createClient();
        $client->request('DELETE', '/evenements/delete/10');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
    }

    /**
     * Ce test vérifie que l'endpoint 'PUT /evenements/annule/{id}' fonctionne correctement.
     * On s'attend à une réponse réussie (HTTP 200) lorsqu'on annule un événement.
     */
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