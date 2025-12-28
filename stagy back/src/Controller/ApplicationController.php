<?php

namespace App\Controller;

use App\Entity\Application;
use App\Form\ApplicationType;
use App\Repository\ApplicationRepository;
use Doctrine\ORM\EntityManagerInterface;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/application', name: 'application_')]
class ApplicationController extends AbstractController
{
    
    #[Route('/company/{id}', name: 'applications_by_company', methods: ['GET'])]
    #[OA\Get(
        path: '/api/application/company/{id}',
        summary: 'Get all applications for a company\'s offers',
        description: 'Returns a list of applications where the offer belongs to the given company ID',
        tags: ['Applications']
    )]
    #[OA\Parameter(
        name: 'id',
        in: 'path',
        required: true,
        description: 'Company ID',
        schema: new OA\Schema(type: 'integer')
    )]
    public function getApplicationsByCompany(EntityManagerInterface $entityManager,int $id): JsonResponse {
        // Find all applications where the offer belongs to the company
        $applications = $entityManager->getRepository(Application::class)
            ->createQueryBuilder('a')
            ->join('a.offer', 'o')
            ->where('o.company = :companyId')
            ->setParameter('companyId', $id)
            ->getQuery()
            ->getResult();

        // Optional: Use normalizer if you don't want serialization groups
        $normalized = [];
        foreach ($applications as $application) {
            $normalized[] = [
                'id' => $application->getId(),
                'offer_id' => $application->getOffer()->getId(),
                'offer_title' => $application->getOffer()->getTitle(),
                'student_id' => $application->getStudent()->getId(),
                'student_name' => $application->getStudent()->getFullName(),
                'student_level' =>$application->getStudent()->getLevel(),
                'student_picture' =>$application->getStudent()->getPicture(),
                'status' => $application->getStatus(),
                'compatibility' =>$application->getCompatibility(),
                'timestamp' => $application->getTimestamp() ,
            ];
        }

        return $this->json($normalized);
    }


    #[Route('/{id}/status', name: 'update_application_status', methods: ['PUT'])]
    #[OA\Put(
        path: '/api/application/{id}/status',
        summary: 'Update the status of an application',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['status'],
                properties: [
                    new OA\Property(property: 'status', type: 'string', example: 'accepted')
                ]
            )
        ),
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(response: 200, description: 'Application status updated successfully'),
            new OA\Response(response: 404, description: 'Application not found'),
            new OA\Response(response: 400, description: 'Invalid request'),
        ]
    )]
    public function updateStatus(
        int $id,
        Request $request,
        EntityManagerInterface $em
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);
        if (!isset($data['status'])) {
            return new JsonResponse(['error' => 'Status is required'], Response::HTTP_BAD_REQUEST);
        }

        $application = $em->getRepository(Application::class)->find($id);

        if (!$application) {
            return new JsonResponse(['error' => 'Application not found'], Response::HTTP_NOT_FOUND);
        }

        $application->setStatus($data['status']);
        $em->flush();

        return new JsonResponse(['message' => 'Status updated successfully']);
    }
    #[Route('', name: 'create', methods: ['POST'])]
    #[OA\Post(
        path: '/api/application',
        summary: 'Create new application',
        tags: ['Application'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: 'object',
                required: ['offer', 'student'],
                properties: [
                    new OA\Property(
                        property: 'compatibility',
                        type: 'number',
                        format: 'float',
                        nullable: true,
                        example: 0.85
                    ),
                    new OA\Property(
                        property: 'status',
                        type: 'string',
                        nullable: true,
                        example: 'pending'
                    ),
                    new OA\Property(
                        property: 'timestamp',
                        type: 'string',
                        format: 'date-time',
                        nullable: true,
                        example: '2023-05-15T10:00:00Z'
                    ),
                    new OA\Property(
                        property: 'offer',
                        type: 'integer',
                        example: 1,
                        description: 'ID of the related offer'
                    ),
                    new OA\Property(
                        property: 'student',
                        type: 'integer',
                        example: 1,
                        description: 'ID of the student applicant'
                    )
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Created',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'id', type: 'integer', example: 1),
                        new OA\Property(property: 'offer', type: 'integer', example: 1),
                        new OA\Property(property: 'student', type: 'integer', example: 1)
                    ]
                )
            ),
            new OA\Response(response: 400, description: 'Invalid input')
        ]
    )]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        
        $application = new Application();
        $form = $this->createForm(ApplicationType::class, $application);
        $form->submit($data, false); // false = allow extra fields

        if ($form->isValid()) {
            $em->persist($application);
            $em->flush();
            return new JsonResponse(['message' => 'Aapplication submistted!', 'id' => $application->getId()], 201);
        }

        // Get human-readable errors
        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[$error->getOrigin()->getName()] = $error->getMessage();
        }

        return new JsonResponse(['errors' => $errors], 400);
    }

    #[Route('/{id}', name: 'get_one', methods: ['GET'])]
    #[OA\Get(
        path: '/api/application/{id}',
        summary: 'Get application details',
        tags: ['Application'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', schema: new OA\Schema(type: 'integer'))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Success',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'id', type: 'integer'),
                        new OA\Property(
                            property: 'compatibility',
                            type: 'number',
                            format: 'float',
                            nullable: true
                        ),
                        new OA\Property(
                            property: 'status',
                            type: 'string',
                            nullable: true
                        ),
                        new OA\Property(
                            property: 'timestamp',
                            type: 'string',
                            format: 'date-time',
                            nullable: true
                        ),
                        new OA\Property(
                            property: 'offer',
                            type: 'integer',
                            description: 'Related offer ID'
                        ),
                        new OA\Property(
                            property: 'student',
                            type: 'integer',
                            description: 'Student applicant ID'
                        )
                    ]
                )
            ),
            new OA\Response(response: 404, description: 'Not found')
        ]
    )]
    public function getOne(int $id, ApplicationRepository $repo): JsonResponse
    {
        if (!$application = $repo->find($id)) {
            return new JsonResponse(['message' => 'Not found'], 404);
        }

        return new JsonResponse([
            'id' => $application->getId(),
            'compatibility' => $application->getCompatibility(),
            'status' => $application->getStatus(),
            'timestamp' => $application->getTimestamp(),
            'offer' => $application->getOffer()?->getId(),
            'student' => $application->getStudent()?->getId()
        ]);
    }
    #[Route('/{id}', name: 'update', methods: ['PUT'])]
    #[OA\Put(
        path: '/api/application/{id}',
        summary: 'Update application',
        tags: ['Application'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', schema: new OA\Schema(type: 'integer'))
        ],
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                type: 'object',
                properties: [
                    new OA\Property(property: 'compatibility', type: 'number', nullable: true),
                    new OA\Property(property: 'status', type: 'string', nullable: true),
                    new OA\Property(property: 'timestamp', type: 'string', format: 'date-time', nullable: true),
                    new OA\Property(property: 'offer', type: 'integer', nullable: true),
                    new OA\Property(property: 'student', type: 'integer', nullable: true)
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Updated'),
            new OA\Response(response: 400, description: 'Invalid input'),
            new OA\Response(response: 404, description: 'Not found')
        ]
    )]
    public function update(int $id, Request $request, EntityManagerInterface $em, ApplicationRepository $repo): JsonResponse
    {
        if (!$application = $repo->find($id)) {
            return new JsonResponse(['message' => 'Application not found'], 404);
        }

        $form = $this->createForm(ApplicationType::class, $application);
        $form->submit(json_decode($request->getContent(), true), false);

        if ($form->isValid()) {
            $em->flush();
            return new JsonResponse([
                'id' => $application->getId(),
                'offer' => $application->getOffer()?->getId(),
                'student' => $application->getStudent()?->getId()
            ]);
        }

        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[$error->getOrigin()->getName()] = $error->getMessage();
        }

        return new JsonResponse(['errors' => $errors], 400);
    }

    // DELETE (DELETE)
    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    #[OA\Delete(
        path: '/api/application/{id}',
        summary: 'Delete application',
        tags: ['Application'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', schema: new OA\Schema(type: 'integer'))
        ],
        responses: [
            new OA\Response(response: 204, description: 'Deleted'),
            new OA\Response(response: 404, description: 'Not found')
        ]
    )]
    public function delete(int $id, EntityManagerInterface $em, ApplicationRepository $repo): JsonResponse
    {
        if (!$application = $repo->find($id)) {
            return new JsonResponse(['message' => 'Application not found'], 404);
        }

        $em->remove($application);
        $em->flush();

        return new JsonResponse(null, 204);
    }
      #[Route('', name: 'get_all', methods: ['GET'])]
    #[OA\Get(
        path: '/api/application',
        summary: 'List all applications',
        tags: ['Application'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Success',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(
                        type: 'object',
                        properties: [
                            new OA\Property(property: 'id', type: 'integer'),
                            new OA\Property(property: 'compatibility', type: 'number', nullable: true),
                            new OA\Property(property: 'status', type: 'string', nullable: true),
                            new OA\Property(property: 'timestamp', type: 'string', format: 'date-time', nullable: true),
                            new OA\Property(property: 'offer', type: 'integer'),
                            new OA\Property(property: 'student', type: 'integer')
                        ]
                    )
                )
            )
        ]
    )]
    public function getAll(ApplicationRepository $repo): JsonResponse
    {
        $applications = $repo->findAll();
        $data = [];

        foreach ($applications as $application) {
            $data[] = [
                'id' => $application->getId(),
                'compatibility' => $application->getCompatibility(),
                'status' => $application->getStatus(),
                'timestamp' => $application->getTimestamp(),
                'offer' => $application->getOffer()?->getId(),
                'student' => $application->getStudent()?->getId()
            ];
        }

        return new JsonResponse($data);
    }

}

    
