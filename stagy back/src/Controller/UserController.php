<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/*
#[Route('/api/users')]*/
final class UserController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserRepository $userRepository,
        private SerializerInterface $serializer,
    ) {
    }

    


    
#[Route('/api/user', name: 'get_username', methods: ['GET'])]
#[OA\Get(
    path: '/api/user',
    summary: 'Get user by username',
    tags: ['User'],
    responses: [
        new OA\Response(response: 200, description: 'student found'),
        new OA\Response(response: 204, description: 'student not found'),
    ]
)]
public function getUsername(UserRepository $SRepo): JsonResponse
{
    
         $user = $this->getUser();

   
    $username = $user->getUserIdentifier(); 

    $S = $SRepo->findOneBy(criteria: ['username' => $username]);
     if (!$S) {
        return new JsonResponse(['message' => 'user not found'], 404);
    }
    return new JsonResponse([
        'id' => $S->getId(),
        'username' => $S->getUsername(),
              
    ],200);

    }



}
