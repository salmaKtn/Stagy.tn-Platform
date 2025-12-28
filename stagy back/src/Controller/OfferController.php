<?php

namespace App\Controller;

use App\Entity\Offer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;
use App\Form\OfferType;
use App\Repository\OfferRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

#[Route('/api/offer', name: 'offer_')]
class OfferController extends AbstractController
{
    #[Route('', name: 'create', methods: ['POST'])]
    #[OA\Post(
        path: '/api/offer',
        summary: 'Create a new offer',
        tags: ['Offer'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: 'object',
                required: ['title', 'company'],
                properties: [
                    new OA\Property(property: 'title', type: 'string', example: 'Software Engineer'),
                    new OA\Property(property: 'company', type: 'integer', example: 1),
                    new OA\Property(property: 'description', type: 'string', nullable: true),
                    new OA\Property(property: 'payment', type: 'integer', nullable: true),
                    new OA\Property(property: 'duration', type: 'string', example: '6 months')
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Created',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'id', type: 'integer', example: 1)
                    ]
                )
            ),
            new OA\Response(response: 400, description: 'Invalid data')
        ]
    )]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $offer = new Offer();
        $form = $this->createForm(OfferType::class, $offer);
        $form->submit(json_decode($request->getContent(), true));

        if ($form->isValid()) {
            $em->persist($offer);
            $em->flush();
            return new JsonResponse(['id' => $offer->getId()], 201);
        }

        return new JsonResponse(['errors' => (string) $form->getErrors(true)], 400);
    }

    #[Route('/{id}', name: 'update', methods: ['PUT'])]
    #[OA\Put(
        path: '/api/offer/{id}',
        summary: 'Update offer',
        tags: ['Offer'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', schema: new OA\Schema(type: 'integer'))
        ],
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                type: 'object',
                properties: [
                    new OA\Property(property: 'title', type: 'string', nullable: true),
                    new OA\Property(property: 'company', type: 'integer', nullable: true),
                    // Include other fields as needed...
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Updated'),
            new OA\Response(response: 404, description: 'Not found'),
            new OA\Response(response: 400, description: 'Invalid data')
        ]
    )]
    public function update(int $id, Request $request, EntityManagerInterface $em, OfferRepository $repo): JsonResponse
    {
        if (!$offer = $repo->find($id)) {
            return new JsonResponse(['message' => 'Not found'], 404);
        }

        $form = $this->createForm(OfferType::class, $offer);
        $form->submit(json_decode($request->getContent(), true), false);

        if ($form->isValid()) {
            $em->flush();
            return new JsonResponse(null, 200);
        }

        return new JsonResponse(['errors' => (string) $form->getErrors(true)], 400);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    #[OA\Delete(
        path: '/api/offer/{id}',
        summary: 'Delete offer',
        tags: ['Offer'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', schema: new OA\Schema(type: 'integer'))
        ],
        responses: [
            new OA\Response(response: 204, description: 'Deleted'),
            new OA\Response(response: 404, description: 'Not found')
        ]
    )]
    public function delete(int $id, EntityManagerInterface $em, OfferRepository $repo): JsonResponse
    {
        if (!$offer = $repo->find($id)) {
            return new JsonResponse(['message' => 'Not found'], 404);
        }

        $em->remove($offer);
        $em->flush();
        return new JsonResponse(null, 204);
    }

    #[Route('', name: 'get_all', methods: ['GET'])]
    #[OA\Get(
        path: '/api/offer',
        summary: 'List all offers',
        tags: ['Offer'],
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
                            new OA\Property(property: 'title', type: 'string'),
                            new OA\Property(property: 'company', type: 'integer'),
                            new OA\Property(
                                property: 'applications', 
                                type: 'array',
                                items: new OA\Items(type: 'integer')
                            )
                        ]
                    )
                )
            )
        ]
    )]
    public function getAll(OfferRepository $repo): JsonResponse
    {
        $offers = $repo->createQueryBuilder('o')
    ->leftJoin('o.company', 'c')
    ->addSelect('c')
    ->getQuery()
    ->getResult();
        $data = [];

        foreach ($offers as $offer) {
            $appIds = [];
            foreach ($offer->getApplications() as $app) {
                $appIds[] = $app->getId();
            }

            $data[] = 
                            [
                'id' => $offer->getId(),
                'title' => $offer->getTitle(),
                'company' => $offer->getCompany()?->getId(),
                'companyName' => $offer->getCompany()?->getName(),
                'applications' => $appIds,
                'Roles' => $offer->getRoles(),
                'image' => $offer->getImage(),
                'processStep' => $offer->getProcessStep(),
                'workingHours' => $offer->getWorkingHours(),
                'payment' => $offer->getPayment(),
                'description' => $offer->getDescription(),
                'location' => $offer->getLocation(),
                'type' => $offer->getType(),
                'startDate' => $offer->getStartDate(),
                'endDate' => $offer->getEndDate(),
                'duration' => $offer->getDuration(),
                'category' => $offer->getCategory(),
                'governorate' => $offer->getGovernorate(),
                'languages' => $offer->getLanguages(),
                'fieldsOfInterest' => $offer->getFieldsOfInterest(),
                'skills' => $offer->getSkills()
            
            ];
        }

        return new JsonResponse($data);
    }

    #[Route('/{id}', name: 'get_one', methods: ['GET'])]
    #[OA\Get(
        path: '/api/offer/{id}',
        summary: 'Get offer details',
        tags: ['Offer'],
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
                        new OA\Property(property: 'title', type: 'string'),
                        new OA\Property(property: 'company', type: 'integer'),
                        new OA\Property(
                            property: 'applications',
                            type: 'array',
                            items: new OA\Items(type: 'integer')
                        )
                    ]
                )
            ),
            new OA\Response(response: 404, description: 'Not found')
        ]
    )]
    public function getOne(int $id, OfferRepository $repo): JsonResponse
    {
        if (!$offer = $repo->find($id)) {
            return new JsonResponse(['message' => 'Not found'], 404);
        }

        $appIds = [];
        foreach ($offer->getApplications() as $app) {
            $appIds[] = $app->getId();
        }

        return new JsonResponse([
            'id' => $offer->getId(),
            'title' => $offer->getTitle(),
            'company' => $offer->getCompany()?->getId(),
            'skills' => $offer->getSkills(),
            'applications' => $appIds,
            'Roles' => $offer->getRoles(),
            'image' => $offer->getImage(),
            'processStep' => $offer->getProcessStep(),
            'workingHours' => $offer->getWorkingHours(),
            'payment' => $offer->getPayment(),
            'description' => $offer->getDescription(),
            'location' => $offer->getLocation(),
            'type' => $offer->getType(),
            'startDate' => $offer->getStartDate(),
            'endDate' => $offer->getEndDate(),
            'duration' => $offer->getDuration(),
            'category' => $offer->getCategory(),
            'governorate' => $offer->getGovernorate(),
            'languages' => $offer->getLanguages(),
            'fieldsOfInterest' => $offer->getFieldsOfInterest(),
        ]);
    }
}