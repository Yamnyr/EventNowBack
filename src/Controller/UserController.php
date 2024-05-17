<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/users')]
class UserController extends AbstractController
{
    #[Route('/getall', name: 'app_users_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        $userData = [];

        foreach ($users as $user) {
            $inscriptionData = [];

            foreach ($user->getInscriptions() as $inscription) {
                $inscriptionData[] = [
                    'id' => $inscription->getId(),
                    'date' => $inscription->getDate()->getId(),
                    'nbr_pers' => $inscription->getNombrePersonnes(),
                    'date_inscription' => $inscription->getDateInscription(),
                    'certif' => $inscription->getCertifAgeRequis()
                ];
            }

            $userData[] = [
                'id' => $user->getId(),
                'nom' => $user->getNom(),
                'prenom' => $user->getPrenom(),
                'email' => $user->getEmail(),
                'password'=> $user->getPassword(),
                'role' => $user->getRoles(),
                'date_naissance' => $user->getDateNaissance(),
                'inscriptions' => $inscriptionData,
            ];
        }

        return $this->json($userData);
    }

/*test*/
    #[Route('/getone/{id}', name: 'app_user_index', methods: ['GET'])]
    public function show(UserRepository $userRepository, $id): Response
    {
        $user = $userRepository->find($id);

        $userData = [];

        $inscriptionData = [];

        foreach ($user->getInscriptions() as $inscription) {
            $inscriptionData[] = [
                'id' => $inscription->getId(),
                'date' => $inscription->getDate()->getId(),
                'nbr_pers' => $inscription->getNombrePersonnes(),
                'date_inscription' => $inscription->getDateInscription(),
                'certif' => $inscription->getCertifAgeRequis()
            ];
        }

        $userData[] = [
            'id' => $user->getId(),
            'nom' => $user->getNom(),
            'prenom' => $user->getPrenom(),
            'email' => $user->getEmail(),
            'role' => $user->getRoles(),
            'date_naissance' => $user->getDateNaissance(),
            'inscriptions' => $inscriptionData,
        ];

        return $this->json($userData);
    }





}