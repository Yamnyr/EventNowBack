<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TypeControllerTest extends WebTestCase
{
    /**
     * Ce test vérifie que l'endpoint 'GET /types/getall' fonctionne correctement.
     * On s'attend à une réponse réussie (HTTP 200) lorsqu'on demande tous les types.
     */
    public function testGetAllTypes()
    {
        $client = static::createClient();
        $client->request('GET', '/types/getall');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
    }
}