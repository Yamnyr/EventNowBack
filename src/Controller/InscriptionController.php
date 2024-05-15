<?php

namespace App\Controller;

use App\Entity\Inscription;
use App\Entity\User;
use App\Repository\DateRepository;
use App\Repository\InscriptionRepository;
use App\Repository\TypeRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/inscriptions')]
class InscriptionController extends AbstractController
{
    /*
        GET
        http://127.0.0.1:8000/inscriptions/getall
    */
    #[Route('/getall', name: 'app_inscriptions_index', methods: ['GET'])]
    public function index(InscriptionRepository $inscriptionRepository): Response
    {
        $inscriptions = $inscriptionRepository->findAll();

        $inscriptionsdata = [];
        foreach ($inscriptions as $inscription) {
            $inscriptionsdata[] = [
                'id' => $inscription->getId(),
                'user' => [
                    'id' => $inscription->getUser()->getId(),
                    'nom' => $inscription->getUser()->getNom(),
                    'prenom' => $inscription->getUser()->getNom(),
                    'email' => $inscription->getUser()->getEmail(),
                    'role' => $inscription->getUser()->getRoles(),
                    'date_naissance' => $inscription->getUser()->getDateNaissance(),
                ],
                'date' => [
                    'id' => $inscription->getDate()->getId(),
                    'date' => $inscription->getDate()->getDate(),
                    'evenement' => $inscription->getDate()->getEvenement()->getId()
                ],
                'certif' => $inscription->getCertifAgeRequis(),
                'date_inscription' => $inscription->getDateInscription(),
            ];
        }

        return $this->json($inscriptionsdata);
    }


    /*
    GET
    http://127.0.0.1:8000/inscriptions/getone/1
    */
    #[Route('/getone/{id}', name: 'app_inscription_get', methods: ['GET'])]
    public function getInscription(int $id, InscriptionRepository $inscriptionRepository): Response
    {
        $inscription = $inscriptionRepository->find($id);

        if (!$inscription) {
            // Retourner une réponse avec un message d'erreur si l'inscription n'est pas trouvée
            return $this->json(['message' => 'Inscription non trouvée'], 404);
        }

        // Construire les données de l'inscription
        $inscriptionData = [
            'id' => $inscription->getId(),
            'user' => [
                'id' => $inscription->getUser()->getId(),
                'nom' => $inscription->getUser()->getNom(),
                'prenom' => $inscription->getUser()->getPrenom(),
                'email' => $inscription->getUser()->getEmail(),
                'role' => $inscription->getUser()->getRoles(),
                'date_naissance' => $inscription->getUser()->getDateNaissance(),
            ],
            'date' => [
                'id' => $inscription->getDate()->getId(),
                'date' => $inscription->getDate()->getDate(),
                'evenement' => $inscription->getDate()->getEvenement()->getId()
            ],
            'certif' => $inscription->getCertifAgeRequis(),
            'date_inscription' => $inscription->getDateInscription(),
        ];

        // Retourner les données de l'inscription au format JSON
        return $this->json($inscriptionData);
    }

    /*
    POST
    http://127.0.0.1:8000/inscriptions/add
    {
        "user_id": 1,
        "date_id": 1,
        "certif": true,
        "nombre_pers": 3
    }
    */
    #[Route('/add', name: 'app_inscription_add', methods: ['POST'])]
    public function inscription(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository, DateRepository $dateRepository): Response
    {
        $data = json_decode($request->getContent(), true);

        $user = $userRepository->find($data['user_id']);
        $date = $dateRepository->find($data['date_id']);

        if (!$user || !$date) {
            return $this->json(['message' => 'Utilisateur ou date introuvable'], 404);
        }

        $dateNaissance = $user->getDateNaissance();
        $aujourdHui = new DateTime();
        $age = $aujourdHui->diff($dateNaissance)->y;
        //TODO: gerer la gestion de place (les places restatntes sont stocké dans la table date


        if ($data['nombre_pers'] == 1 && $age > $date->getEvenement()->getAgeRequis()) {
            return $this->json(['message' => 'L\'utilisateur n\'a pas l\'âge requis pour participer à cet événement'], 400);
        } elseif ($data['nombre_pers'] > 1 && !$data['certif']) {
            return $this->json(['message' => 'La certification est requise pour chaque participant'], 400);
        } else {
            $inscription = new Inscription();
            $inscription->setUser($user);
            $inscription->setDate($date);
            $inscription->setDateInscription(new \DateTime());
            $inscription->setNombrePersonnes($data['nombre_pers']);
            $inscription->setCertifAgeRequis($data['certif']);

            $entityManager->persist($inscription);
            $entityManager->flush();

            //TODO: il faut set la place_restante a -nombre d'inscrit en plus
        }

        // Retourner une réponse indiquant le succès de l'inscription

        $response = new Response(json_encode(['message' => 'Inscription réussie']));
        $response->headers->set('Content-Type', 'application/json');
        // Autoriser les requêtes depuis localhost:3000
        $response->headers->set('Access-Control-Allow-Origin', 'http://localhost:3000');

        return $response;
    }


    /*
    GET
    http://127.0.0.1:8000/inscriptions/user/1/inscriptions
    */
    #[Route('/user/{id}/inscriptions', name: 'app_user_inscriptions', methods: ['GET'])]
    public function getUserInscriptions(int $id, InscriptionRepository $inscriptionRepository, UserRepository $userRepository): Response
    {
        // Trouver l'utilisateur correspondant à l'ID
        $user = $userRepository->find($id);

        // Vérifier si l'utilisateur existe
        if (!$user) {
            // Retourner une réponse avec un message d'erreur si l'utilisateur n'est pas trouvé
            return $this->json(['message' => 'Utilisateur non trouvé'], 404);
        }

        // Trouver toutes les inscriptions de l'utilisateur
        $inscriptions = $inscriptionRepository->findBy(['user' => $user]);

        // Construire un tableau pour stocker les données de chaque inscription
        $inscriptionsData = [];

        // Parcourir chaque inscription et construire les données
        foreach ($inscriptions as $inscription) {
            // Construire les données de l'inscription
            $inscriptionData = [
                'id' => $inscription->getId(),
                'date' => [
                    'id' => $inscription->getDate()->getId(),
                    'date' => $inscription->getDate()->getDate(),
                    'evenement' => [
                        'id' => $inscription->getDate()->getEvenement()->getId(),
                        'nom' => $inscription->getDate()->getEvenement()->getNom(),
                        'image' => $inscription->getDate()->getEvenement()->getImage(),
                    ],
                ],
                'certif' => $inscription->getCertifAgeRequis(),
                'date_inscription' => $inscription->getDateInscription(),
            ];

            // Ajouter les données de l'inscription au tableau des données d'inscription
            $inscriptionsData[] = $inscriptionData;
        }

        // Retourner les données des inscriptions de l'utilisateur au format JSON
        return $this->json($inscriptionsData);
    }
}