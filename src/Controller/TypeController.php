<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\TypeRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/types')]
class TypeController extends AbstractController
{

    /*
    GET
    http://127.0.0.1:8000/types/getall
    */
    #[Route('/getall', name: 'app_types_index', methods: ['GET'])]
    public function index(TypeRepository $typeRepository): Response
    {
        $types = $typeRepository->findAll();

        $typesdata = [];
        foreach ($types as $type) {
            $typesdata[] = [
                'id' => $type->getId(),
                'nom' => $type->getNom(),
            ];
        }

        $response = new Response(json_encode($typesdata));
        $response->headers->set('Content-Type', 'application/json');
        // Autoriser les requêtes depuis localhost:3000
        $response->headers->set('Access-Control-Allow-Origin', 'http://localhost:3000');

        return $response;

    }
}