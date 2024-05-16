<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DateControllerTest extends WebTestCase
{
    /**
     * Ce test vérifie que l'endpoint 'GET /dates/getall' fonctionne correctement.
     * On s'attend à une réponse réussie (HTTP 200) lorsqu'on demande toutes les dates.
     */
    public function testGetAllDates(): void
    {
        $client = static::createClient();

        $client->request('GET', '/dates/getall');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertResponseHeaderSame('Content-Type', 'application/json');

        $content = $client->getResponse()->getContent();

        $responseData = json_decode($content, true);

        $this->assertIsArray($responseData);

        // Vérification que chaque élément du tableau contient les clés 'id', 'date', 'evenement', 'places_rest'
        foreach ($responseData as $dateData) {
            $this->assertArrayHasKey('id', $dateData);
            $this->assertArrayHasKey('date', $dateData);
            $this->assertArrayHasKey('evenement', $dateData);
            $this->assertArrayHasKey('places_rest', $dateData);

            // Vérification que les clés 'id', 'nom', 'lieu' sont présentes dans le sous-tableau 'evenement'
            $this->assertArrayHasKey('id', $dateData['evenement']);
            $this->assertArrayHasKey('nom', $dateData['evenement']);
            $this->assertArrayHasKey('lieu', $dateData['evenement']);
        }
    }

    /**
     * Ce test vérifie que l'endpoint 'GET /dates/getone/{id}' fonctionne correctement avec un ID valide.
     * On s'attend à une réponse réussie (HTTP 200) lorsqu'on demande une date avec un ID valide.
     */
    public function testGetOneDate()
    {
        $client = static::createClient();

        $client->request('GET', '/dates/getone/ 2');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertResponseHeaderSame('Content-Type', 'application/json');

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertCount(1, $responseData);

        $this->assertArrayHasKey('id', $responseData[0]);
        $this->assertArrayHasKey('date', $responseData[0]);
        $this->assertArrayHasKey('evenement', $responseData[0]);
        $this->assertArrayHasKey('places_rest', $responseData[0]);

        $this->assertArrayHasKey('id', $responseData[0]['evenement']);
        $this->assertArrayHasKey('nom', $responseData[0]['evenement']);
        $this->assertArrayHasKey('lieu', $responseData[0]['evenement']);
        $this->assertArrayHasKey('image', $responseData[0]['evenement']);
    }

    /**
     * Ce test vérifie que l'endpoint 'GET /dates/getone/{id}' retourne une erreur comme prévu avec un ID invalide.
     * On s'attend à une réponse d'erreur (HTTP 400) lorsqu'on demande une date avec un ID invalide.
     */
    public function testGetDateNotExist()
    {
        $client = static::createClient();
        $client->request('GET', '/dates/getone/1000');

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }
}