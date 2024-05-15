<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\DateRepository;
use App\Repository\TypeRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/dates')]
class DateController extends AbstractController
{
    /*
    GET
    http://127.0.0.1:8000/dates/getall
    */
    #[Route('/getall', name: 'app_dates_index', methods: ['GET'])]
    public function index(DateRepository $dateRepository): Response
    {
        $dates = $dateRepository->findAll();
        $datesData = [];
        foreach ($dates as $date) {
            $evenement = $date->getEvenement();
            $datesData[] = [
                'id' => $date->getId(),
                'date' => $date->getDate(),
                'evenement' => [
                    'id' => $evenement->getId(),
                    'nom' => $evenement->getNom(),
                    'lieu' => $evenement->getLieu()
                ],
                'places_rest' => $date->getPlacesRestantes(),
            ];
        }

        $response = new Response(json_encode($datesData));
        $response->headers->set('Content-Type', 'application/json');
        // Autoriser les requêtes depuis localhost:3000
        $response->headers->set('Access-Control-Allow-Origin', 'http://localhost:3000');

        return $response;
    }

    /*
    GET
    http://127.0.0.1:8000/dates/getone/1
    */
    #[Route('/getone/{id}', name: 'app_date_index', methods: ['GET'])]
    public function show(DateRepository $dateRepository, $id): Response
    {
        $date = $dateRepository->find($id);

        if (!$date) {
            return $this->json(['message' => 'date inexistante'], 400);
        }

        $datesData = [];
        $evenement = $date->getEvenement();
        $datesData[] = [
            'id' => $date->getId(),
            'date' => $date->getDate(),
            'evenement' => [
                'id' => $evenement->getId(),
                'nom' => $evenement->getNom(),
                'lieu' => $evenement->getLieu(),
                'image' => $evenement->getImage()
            ],
            'places_rest' => $date->getPlacesRestantes(),
        ];

        $response = new Response(json_encode($datesData));
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', 'http://localhost:3000');

        return $response;
    }
}