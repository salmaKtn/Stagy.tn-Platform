<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Repository\AdminRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/users')]
final class AdminController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private AdminRepository $AdminRepository,
        private SerializerInterface $serializer,
    ) {
    }
    /*
     * Get one user by id
     * GET /api/users/{id}.  BY ROLE ADMIN
     */
    /*
     * #[Route('/{id}', name: 'api_user_show', methods: ['GET'])]
     *public function show(User $user): JsonResponse
     *{
     *    $data = $this->serializer->serialize($user, 'json', ['groups' => 'user:read']);
     *
     *       return new JsonResponse($data, 200, [], true);
     *  }
     */
}
