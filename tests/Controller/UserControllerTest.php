<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    /**
     * Ce test vérifie que l'endpoint 'GET /users/getall' fonctionne correctement.
     * On s'attend à une réponse réussie (HTTP 200) lorsqu'on demande tous les utilisateurs.
     */
    public function testGetAllUsers()
    {
        $client = static::createClient();
        $client->request('GET', '/users/getall');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
    }

    /**
     * Ce test vérifie que l'endpoint 'GET /users/getone/{id}' fonctionne correctement avec un ID valide.
     * On s'attend à une réponse réussie (HTTP 200) lorsqu'on demande un utilisateur avec un ID valide.
     */
    public function testGetOneUser()
    {
        $client = static::createClient();
        $client->request('GET', '/users/getone/1');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
    }
}