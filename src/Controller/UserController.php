<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

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
                'role' => $user->getRoles(),
                'date_naissance' => $user->getDateNaissance(),
                'inscriptions' => $inscriptionData,
            ];
        }

        return $this->json($userData);
    }

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

    #[Route('/add', name: 'app_user_add', methods: ['POST'])]
    public function addUser(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $data = json_decode($request->getContent(), true);

        if (!$data) {
            return $this->json(['message' => 'Invalid JSON'], Response::HTTP_BAD_REQUEST);
        }

        if (!isset($data['nom'], $data['prenom'], $data['email'], $data['password'], $data['date_naissance'])) {
            return $this->json(['message' => 'Missing data'], Response::HTTP_BAD_REQUEST);
        }

        $user = new User();
        $user->setNom($data['nom']);
        $user->setPrenom($data['prenom']);
        $user->setEmail($data['email']);
        $user->setDateNaissance(new \DateTime($data['date_naissance']));
        $user->setRoles(['ROLE_USER']);

        // Encodage du mot de passe
        $encodedPassword = $passwordHasher->hashPassword($user, $data['password']);
        $user->setPassword($encodedPassword);

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->json(['message' => 'Utilisateur créé avec succès']);
    }
}
