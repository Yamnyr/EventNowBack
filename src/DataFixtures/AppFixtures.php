<?php

namespace App\DataFixtures;

use App\Entity\Date;
use App\Entity\Evenement;
use App\Entity\Inscription;
use App\Entity\Type;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $password
    ){
    }

    public function load(ObjectManager $manager): void
    {

        $usersData = [
            ['email' => 'user1@example.com', 'roles' => ['ROLE_USER'], 'date_naissance' => new \DateTime('1990-01-01'), 'nom' => 'User 1', 'prenom' => 'prenom 1'],
            ['email' => 'user2@example.com', 'roles' => ['ROLE_USER'], 'date_naissance' => new \DateTime('1995-05-10'), 'nom' => 'User 2', 'prenom' => 'prenom 1'],
            // Add more users if needed
        ];

        foreach ($usersData as $userData) {
            $user = new User();
            $user->setEmail($userData['email']);
            $user->setRoles($userData['roles']);
            $user->setDateNaissance($userData['date_naissance']);
            $user->setNom($userData['nom']);
            $user->setPrenom($userData['prenom']);

            // Hash password
            $hashedPassword = $this->password->hashPassword($user, 'test');
            $user->setPassword($hashedPassword);

            $manager->persist($user);
            $this->addReference('user_' . $userData['email'], $user);
        }



        $typesData = [
            ['nom' => 'concert'],
            ['nom' => 'festival'],
            ['nom' => 'brocante'],
            ['nom' => 'jeux vidéo'],
        ];

        foreach ($typesData as $typeData) {
            $type = new Type();
            $type->setNom($typeData['nom']);
            $manager->persist($type);
            $this->addReference('type_' . $typeData['nom'], $type);
        }

        $evenementsData = [
            ['type' => $this->getReference('type_concert'), 'nom' => 'Concert Rock', 'description' => 'Description du concert rock', 'lieu' => 'Lieu du concert', 'annule' => false, 'raison_annulation' => null, 'age_requis' => 18, 'image' => 'image1.jpg'],
        ];

        foreach ($evenementsData as $evenementData) {
            $evenement = new Evenement();
            $evenement->setType($evenementData['type']);
            $evenement->setNom($evenementData['nom']);
            $evenement->setDescription($evenementData['description']);
            $evenement->setLieu($evenementData['lieu']);
            $evenement->setAnnule($evenementData['annule']);
            $evenement->setRaisonAnnulation($evenementData['raison_annulation']);
            $evenement->setAgeRequis($evenementData['age_requis']);
            $evenement->setImage($evenementData['image']);
            $manager->persist($evenement);
            $this->addReference('evenement_' . $evenementData['nom'], $evenement);
        }

        // Create Date fixtures
        $datesData = [
            ['evenement' => $this->getReference('evenement_Concert Rock'), 'date' => new \DateTime('2024-06-15'), 'places_restantes' => 100],
            // Add more dates if needed
        ];

        foreach ($datesData as $dateData) {
            $date = new Date();
            $date->setEvenement($dateData['evenement']);
            $date->setDate($dateData['date']);
            $date->setPlacesRestantes($dateData['places_restantes']);
            $manager->persist($date);
            $this->addReference('date_' . $dateData['evenement']->getNom(), $date);
        }



        $inscriptionsData = [
            ['utilisateur' => $this->getReference('user_user1@example.com'), 'date' => $this->getReference('date_Concert Rock'), 'nombre_personnes' => 2, 'age_requis' => true, 'date_inscription' => new \DateTime()],
            // Add more inscriptions if needed
        ];

        foreach ($inscriptionsData as $inscriptionData) {
            $inscription = new Inscription();
            $inscription->setUser($inscriptionData['utilisateur']);
            $inscription->setDate($inscriptionData['date']);
            $inscription->setNombrePersonnes($inscriptionData['nombre_personnes']);
            $inscription->setCertifAgeRequis($inscriptionData['age_requis']);
            $inscription->setDateInscription($inscriptionData['date_inscription']);
//            $inscription->setUuid($inscriptionData['uuid']);
            $manager->persist($inscription);
        }

        // Flush all the objects to database
        $manager->flush();
    }
}
