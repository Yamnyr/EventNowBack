<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Entity\User;
use App\Repository\DateRepository;
use App\Repository\EvenementRepository;
use App\Repository\TypeRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Date;
use Symfony\Component\Security\Http\Attribute\IsGranted;

// Assurez-vous d'importer la classe Date


#[Route('/evenements')]
class EvenementController extends AbstractController
{

    /*
    GET
    http://127.0.0.1:8000/evenements/getall
    */
    #[Route('/getall', name: 'app_evenements_index', methods: ['GET'])]
    public function index(EvenementRepository $evenementRepository): Response
    {
        $evenements = $evenementRepository->findAll();

        $evenementsData = [];
        foreach ($evenements as $evenement) {

            $datesData = [];
            foreach ($evenement->getDates() as $date) {
                $datesData[] = [
                    'id' => $date->getId(),
                    'date' => $date->getDate(),
                    'places_rest' => $date->getPlacesRestantes(),
                ];
            }

            $evenementsData[] = [
                'id' => $evenement->getId(),
                'nom' => $evenement->getNom(),
                'description' => $evenement->getDescription(),
                'lieu' => $evenement->getLieu(),
                'annule' => $evenement->getAnnule(),
                'raison_annulation' => $evenement->getRaisonAnnulation(),
                'age_requis' => $evenement->getAgeRequis(),
                'image' => $evenement->getImage(),
                'type' => $evenement->getType()->getNom(),
                'dates' => $datesData
            ];
        }
        $response = new Response(json_encode($evenementsData));
        $response->headers->set('Content-Type', 'application/json');
        // Autoriser les requêtes depuis localhost:3000
        $response->headers->set('Access-Control-Allow-Origin', 'http://localhost:3000');

        return $response;

    }

    /*
    GET
    http://127.0.0.1:8000/evenements/getone/2
    */
    #[Route('/getone/{id}', name: 'app_evenement_index', methods: ['GET'])]
    public function show(EvenementRepository $evenementRepository, $id): Response
    {
        $evenement = $evenementRepository->find($id);

        $datesData = [];
        foreach ($evenement->getDates() as $date) {
            $datesData[] = [
                'id' => $date->getId(),
                'date' => $date->getDate()->format('Y-m-d'),
                'places_rest' => $date->getPlacesRestantes(),
            ];
        }

        $evenementData[] = [
            'id' => $evenement->getId(),
            'nom' => $evenement->getNom(),
            'description' => $evenement->getDescription(),
            'lieu' => $evenement->getLieu(),
            'annule' => $evenement->getAnnule(),
            'raison_annulation' => $evenement->getRaisonAnnulation(),
            'age_requis' => $evenement->getAgeRequis(),
            'image' => $evenement->getImage(),
            'type' => $evenement->getType()->getNom(),
            'dates' => $datesData
        ];

        $response = new Response(json_encode($evenementData));
        $response->headers->set('Content-Type', 'application/json');
        // Autoriser les requêtes depuis localhost:3000
        $response->headers->set('Access-Control-Allow-Origin', 'http://localhost:3000');

        return $response;
    }



    /*{
    POST
    http://127.0.0.1:8000/evenements/add
{
    "nom": "event562654",
    "description": "Description de l'événement",
    "lieu": "Lieu de l'événement",
    "type": 1,
    "age_requis": 16,
    "image": "lien_vers_image",
    "dates":[
        {
            "date": "2024-06-15",
            "places_rest" : 200
        },
        {
            "date": "2024-07-15",
            "places_rest" : 300
        }
    ]
}
    */
    #[Route('/add', name: 'app_evenement_add', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, TypeRepository $typeRepository): Response
    {
        $data = json_decode($request->getContent(), true);

        // Créer un nouvel événement
        $evenement = new Evenement();
        $evenement->setNom($data['nom']);
        $evenement->setDescription($data['description']);
        $evenement->setLieu($data['lieu']);
        $evenement->setType($typeRepository->find($data['type']));
        $evenement->setAnnule(false);
        $evenement->setRaisonAnnulation("");
        $evenement->setAgeRequis($data['age_requis']);
        $evenement->setImage($data['image']);

        $entityManager->persist($evenement);

        foreach ($data['dates'] as $dateData) {
            $date = new Date();
            $date->setDate(new \DateTime($dateData['date']));
            $date->setPlacesRestantes($dateData['places_rest']);
            $date->setEvenement($evenement);
            $entityManager->persist($date);
        }

        $entityManager->flush();

        // Retourner une réponse indiquant le succès de l'ajout

        $response = new Response(json_encode(['message' => 'Événement ajouté avec succès']));
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', 'http://localhost:3000');

        return $response;
    }


    /*
        DELETE
        http://127.0.0.1:8000/evenements/delete/3
    */
    #[Route('/delete/{id}', name: 'app_evenement_delete', methods: ['DELETE'])]
    public function delete(Evenement $evenement, EntityManagerInterface $entityManager): Response
    {
        // Supprimer les inscriptions associées aux dates de l'événement
        foreach ($evenement->getDates() as $date) {
            foreach ($date->getInscriptions() as $inscription) {
                $entityManager->remove($inscription);
            }
        }

        // Supprimer les dates associées à l'événement
        foreach ($evenement->getDates() as $date) {
            $entityManager->remove($date);
        }

        // Supprimer l'événement lui-même
        $entityManager->remove($evenement);
        $entityManager->flush();

        // Retourner une réponse indiquant le succès de la suppression
        return $this->json(['message' => 'Événement supprimé avec succès']);
    }


/*
    PUT
    http://127.0.0.1:8000/evenements/annule/2
    {
    "raison_annulation": "Raison de l'annulation de l'événement"
    }
    */
    #[Route('/annule/{id}', name: 'app_evenement_annule', methods: ['PUT'])]
    public function cancelEvent(Evenement $evenement, Request $request, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);

        $raisonAnnulation = isset($data['raison_annulation']) ? $data['raison_annulation'] : '';

        $evenement->setAnnule(true);
        $evenement->setRaisonAnnulation($raisonAnnulation);

        $entityManager->flush();

        return $this->json(['message' => 'Événement annulé avec succès']);
    }

}