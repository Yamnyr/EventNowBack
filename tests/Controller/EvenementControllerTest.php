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

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertIsArray($responseData);
    }

    /**
     * Ce test vérifie que l'endpoint 'GET /evenements/getone/{id}' fonctionne correctement avec un ID valide.
     * On s'attend à une réponse réussie (HTTP 200) lorsqu'on demande un événement avec un ID valide.
     */
    public function testGetOneEvenement()
    {
        $client = static::createClient();
        $client->request('GET', '/evenements/getone/2');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertResponseHeaderSame('Content-Type', 'application/json');

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertIsArray($responseData);
    }


    public function testGetOneEvenementNotExist()
    {
        $client = static::createClient();
        $client->request('GET', '/evenements/getone/999');

        // Vérifier si la réponse a un code de statut 400 (Bad Request)
        $this->assertEquals(400, $client->getResponse()->getStatusCode());

        // Vérifier si le type de contenu de la réponse est JSON
        $this->assertResponseHeaderSame('Content-Type', 'application/json');

        // Vérifier si la réponse contient un message d'erreur approprié
        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals('evenement inexistante', $responseData['message']);
    }

    /**
     * Ce test vérifie que l'endpoint 'POST /evenements/add' fonctionne correctement.
     * On s'attend à une réponse réussie (HTTP 200) lorsqu'on ajoute un nouvel événement.
     */
    public function testAddEvenement()
    {
        $client = static::createClient();

        // Données de l'événement à ajouter
        $eventData = [
            "nom" => "event562654",
            "description" => "Description de l'événement",
            "lieu" => "Lieu de l'événement",
            "type" => 1,
            "age_requis" => 16,
            "image" => "lien_vers_image",
            "dates" => [
                [
                    "date" => "2024-06-15",
                    "places_rest" => 200
                ],
                [
                    "date" => "2024-07-15",
                    "places_rest" => 300
                ]
            ]
        ];

        // Effectuer une requête POST pour ajouter l'événement
        $client->request(
            'POST',
            '/evenements/add',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($eventData)
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertResponseHeaderSame('Content-Type', 'application/json');

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals('Événement ajouté avec succès', $responseData['message']);

    }



    public function testAddEvenementWithhTooMembers()
    {
        $client = static::createClient();

        // Données de l'événement à ajouter
        $eventData = [
            "nom" => "event562654",
            "description" => "Description de l'événement",
            "lieu" => "Lieu de l'événement",
            "type" => 1,
            "age_requis" => 16,
            "image" => "lien_vers_image",
            "dates" => [
                [
                    "date" => "2024-06-15",
                    "places_rest" => 7001
                ]
            ]
        ];

        // Effectuer une requête POST pour ajouter l'événement
        $client->request(
            'POST',
            '/evenements/add',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($eventData)
        );

        $this->assertEquals(400, $client->getResponse()->getStatusCode());

        $this->assertResponseHeaderSame('Content-Type', 'application/json');

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals('Le nombre de places restantes ne peut pas dépasser 7000', $responseData['message']);

    }

//    /**
//     * Ce test vérifie que l'endpoint 'DELETE /evenements/delete/{id}' fonctionne correctement.
//     * On s'attend à une réponse réussie (HTTP 200) lorsqu'on supprime un événement.
//     */
//    public function testDeleteEvenement()
//    {
//        $client = static::createClient();
//        $client->request('DELETE', '/evenements/delete/10');
//
//        $this->assertEquals(200, $client->getResponse()->getStatusCode());
//        $this->assertResponseHeaderSame('Content-Type', 'application/json');
//    }

    /**
     * Ce test vérifie que l'endpoint 'DELETE /evenements/delete/{id}' fonctionne correctement.
     * On s'attend à une réponse réussie (HTTP 200) lorsqu'on supprime un événement.
     */
    public function testDeleteEvenementNotExist()
    {
        $client = static::createClient();
        $client->request('DELETE', '/evenements/delete/10000');

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertResponseHeaderSame('Content-Type', 'application/json');

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals('evenement inexistant', $responseData['message']);
    }


    /**
     * Ce test vérifie que l'endpoint 'PUT /evenements/annule/{id}' fonctionne correctement.
     * On s'attend à une réponse réussie (HTTP 200) lorsqu'on annule un événement.
     */
    public function testCancelEvent()
    {
        $client = static::createClient();
        $client->request('PUT', '/evenements/annule/1000', [], [], [], json_encode([
            'raison_annulation' => 'Test Reason'
        ]));

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
    }
}