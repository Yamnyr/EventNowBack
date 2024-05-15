<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DateControllerTest extends WebTestCase
{
    /**
     * Ce test vérifie que l'endpoint 'GET /dates/getall' fonctionne correctement.
     * On s'attend à une réponse réussie (HTTP 200) lorsqu'on demande toutes les dates.
     */
    public function testGetAllDates()
    {
        $client = static::createClient();
        $client->request('GET', '/dates/getall');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    /**
     * Ce test vérifie que l'endpoint 'GET /dates/getone/{id}' fonctionne correctement avec un ID valide.
     * On s'attend à une réponse réussie (HTTP 200) lorsqu'on demande une date avec un ID valide.
     */
    public function testGetDate()
    {
        $client = static::createClient();
        $client->request('GET', '/dates/getone/1');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    /**
     * Ce test vérifie que l'endpoint 'GET /dates/getone/{id}' retourne une erreur comme prévu avec un ID invalide.
     * On s'attend à une réponse d'erreur (HTTP 400) lorsqu'on demande une date avec un ID invalide.
     */
    public function testGetDateERROR()
    {
        $client = static::createClient();
        $client->request('GET', '/dates/getone/1000');

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }
}