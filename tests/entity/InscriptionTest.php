<?php

namespace App\Tests\Entity;

use App\Entity\Inscription;
use App\Entity\User;
use DateTime;
use PHPUnit\Framework\TestCase;

class InscriptionTest extends TestCase
{
    /**
     * Ce test vérifie que l'ID d'une nouvelle inscription est null.
     */
    public function testGetId()
    {
        $inscription = new Inscription();
        $this->assertNull($inscription->getId());
    }

    /**
     * Ce test vérifie que l'utilisateur d'une inscription peut être correctement défini et récupéré.
     */
    public function testUser()
    {
        $inscription = new Inscription();
        $user = new User();
        $inscription->setUser($user);
        $this->assertEquals($user, $inscription->getUser());
    }

    /**
     * Ce test vérifie que le nombre de personnes d'une inscription peut être correctement défini et récupéré.
     */
    public function testNombrePersonnes()
    {
        $inscription = new Inscription();
        $inscription->setNombrePersonnes(5);
        $this->assertEquals(5, $inscription->getNombrePersonnes());
    }

    /**
     * Ce test vérifie que le certificat d'âge requis d'une inscription peut être correctement défini et récupéré.
     */
    public function testCertifAgeRequis()
    {
        $inscription = new Inscription();
        $inscription->setCertifAgeRequis(true);
        $this->assertTrue($inscription->getCertifAgeRequis());
        $this->assertTrue($inscription->isCertifAgeRequis());
    }

    /**
     * Ce test vérifie que la date d'inscription peut être correctement définie et récupérée.
     */
    public function testDateInscription()
    {
        $inscription = new Inscription();
        $dateInscription = new DateTime();
        $inscription->setDateInscription($dateInscription);
        $this->assertEquals($dateInscription, $inscription->getDateInscription());
    }
}