<?php
//
//namespace App\Tests\Entity;
//
//use App\Entity\User;
//use PHPUnit\Framework\TestCase;
//
//class UserTest extends TestCase
//{
//    public function testConstructor()
//    {
//        // Création d'une instance de l'entité User
//        $user = new User();
//
//        // Vérification que l'instance est bien de la classe User
//        $this->assertInstanceOf(User::class, $user);
//    }
//
//    public function testGettersAndSetters()
//    {
//        // Création d'une instance de l'entité User
//        $user = new User();
//
//        // Définition de valeurs pour les attributs
//        $email = 'john@example.com';
//
//        // Utilisation des setters pour définir les valeurs des attributs
//        $user->setEmail($email);
//
//        // Utilisation des getters pour récupérer les valeurs des attributs
//        $this->assertEquals($email, $user->getEmail());
//    }
//}


namespace App\Tests\Entity;

use App\Entity\Inscription;
use App\Entity\User;
use DateTime;
use PHPUnit\Framework\TestCase;

class InscriptionTest extends TestCase
{
    public function testGetId()
    {
        $inscription = new Inscription();
        $this->assertNull($inscription->getId());
    }

    public function testUser()
    {
        $inscription = new Inscription();
        $user = new User();
        $inscription->setUser($user);
        $this->assertEquals($user, $inscription->getUser());
    }

//    public function testDate()
//    {
//        $inscription = new Inscription();
//        $date = new DateTime();
//        $inscription->setDate($date);
//        $this->assertEquals($date, $inscription->getDate());
//    }

    public function testNombrePersonnes()
    {
        $inscription = new Inscription();
        $inscription->setNombrePersonnes(5);
        $this->assertEquals(5, $inscription->getNombrePersonnes());
    }

    public function testCertifAgeRequis()
    {
        $inscription = new Inscription();
        $inscription->setCertifAgeRequis(true);
        $this->assertTrue($inscription->getCertifAgeRequis());
        $this->assertTrue($inscription->isCertifAgeRequis());
    }

    public function testDateInscription()
    {
        $inscription = new Inscription();
        $dateInscription = new DateTime();
        $inscription->setDateInscription($dateInscription);
        $this->assertEquals($dateInscription, $inscription->getDateInscription());
    }
}