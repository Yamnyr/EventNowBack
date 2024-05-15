<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class InscriptionControllerTest extends WebTestCase
{
    /**
     * Ce test vérifie que l'endpoint 'GET /inscriptions/getone/{id}' fonctionne correctement avec un ID valide.
     * On s'attend à une réponse réussie (HTTP 200) lorsqu'on demande une inscription avec un ID valide.
     */
    public function testGetInscription()
    {
        $client = static::createClient();
        $client->request('GET', 'http://127.0.0.1:8000/inscriptions/getone/1');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    /**
     * Ce test vérifie que l'endpoint 'POST /inscriptions/add' fonctionne correctement.
     * On s'attend à une réponse réussie (HTTP 200) lorsqu'on ajoute une nouvelle inscription.
     */
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

    /**
     * Ce test vérifie que l'endpoint 'POST /inscriptions/add' retourne une erreur comme prévu lorsqu'on ajoute une inscription sans certificat.
     * On s'attend à une réponse d'erreur (HTTP 400) lorsqu'on ajoute une inscription sans certificat.
     */
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

    /**
     * Ce test vérifie que l'endpoint 'GET /inscriptions/user/{id}/inscriptions' fonctionne correctement avec un ID d'utilisateur valide.
     * On s'attend à une réponse réussie (HTTP 200) lorsqu'on demande toutes les inscriptions d'un utilisateur avec un ID valide.
     */
    public function testGetUserInscriptions()
    {
        $client = static::createClient();
        $client->request('GET', '/inscriptions/user/1/inscriptions');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}