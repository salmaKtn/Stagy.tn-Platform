<?php

namespace App\Controller;

use App\Entity\SectionEntry;
use App\Form\SectionEntryType;
use App\Repository\SectionEntryRepository;
use Doctrine\ORM\EntityManagerInterface;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



#[Route('/api/section-entry', name: 'section_entry_')]
class SectionEntryController extends AbstractController
{
    #[Route('', name: 'create', methods: ['POST'])]
    #[OA\Post(
        path: '/api/section-entry',
        summary: 'Create a new Section Entry',
        tags: ['Section Entry'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/SectionEntry')
        ),
        responses: [
            new OA\Response(response: 201, description: 'Section Entry created'),
            new OA\Response(response: 400, description: 'Validation error')
        ]
    )]
    public function create(Request $request, EntityManagerInterface $em, ValidatorInterface $validator): JsonResponse
    {
        $sectionEntry = new SectionEntry();

        $data = json_decode($request->getContent(), true);

        $form = $this->createForm(SectionEntryType::class, $sectionEntry);
        $form->submit($data);

        if (!$form->isValid()) {
            $errors = [];
            foreach ($form->getErrors(true) as $error) {
                $errors[] = $error->getMessage();
            }
            return new JsonResponse(['errors' => $errors], 400);
        }

        $em->persist($sectionEntry);
        $em->flush();

        return new JsonResponse(['message' => 'Section Entry created', 'id' => $sectionEntry->getId()], 201);
    }

    #[Route('/{id}', name: 'update', methods: ['PUT'])]
    #[OA\Put(
        path: '/api/section-entry/{id}',
        summary: 'Update an existing Section Entry',
        tags: ['Section Entry'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer'),
                description: 'ID of the Section Entry'
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/SectionEntry')
        ),
        responses: [
            new OA\Response(response: 200, description: 'Section Entry updated'),
            new OA\Response(response: 400, description: 'Validation error'),
            new OA\Response(response: 404, description: 'Section Entry not found')
        ]
    )]
    public function update(int $id, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $sectionEntry = $em->getRepository(SectionEntry::class)->find($id);
        if (!$sectionEntry) {
            return new JsonResponse(['message' => 'Section Entry not found'], 404);
        }

        $data = json_decode($request->getContent(), true);

        $form = $this->createForm(SectionEntryType::class, $sectionEntry);
        $form->submit($data, false); // false = partial update allowed

        if (!$form->isValid()) {
            $errors = [];
            foreach ($form->getErrors(true) as $error) {
                $errors[] = $error->getMessage();
            }
            return new JsonResponse(['errors' => $errors], 400);
        }

        $em->flush();

        return new JsonResponse(['message' => 'Section Entry updated']);
    }

#[Route('/{id}', name: 'delete_section_entry', methods: ['DELETE'])]
#[OA\Delete(
    path: '/api/section-entry/{id}',
    summary: 'Delete a Section Entry',
    tags: ['Section Entry'],
    parameters: [
        new OA\Parameter(
            name: 'id',
            in: 'path',
            required: true,
            schema: new OA\Schema(type: 'integer'),
            description: 'ID of the Section Entry'
        )
    ],
    responses: [
        new OA\Response(response: 204, description: 'Section Entry deleted'),
        new OA\Response(response: 404, description: 'Section Entry not found'),
    ]
)]
public function delete(int $id, SectionEntryRepository $SERepo, EntityManagerInterface $em): JsonResponse
{
    $SE = $SERepo->find($id);
    if (!$SE) {
        return new JsonResponse(['message' => 'Section Entry not found'], 404);
    }

    $em->remove($SE);
    $em->flush();

    return new JsonResponse(null, 204);
}
#[Route('', name: 'list_section_entries', methods: ['GET'])]
#[OA\Get(
    path: '/api/section-entry',
    summary: 'Get all section entries',
    tags: ['Section Entry'],
    responses: [
        new OA\Response(response: 200, description: 'List of section entries'),
    ]
)]
public function readAll(SectionEntryRepository $SERepo): JsonResponse
{
    $SEs = $SERepo->findAll();
    $data = [];

    foreach ($SEs as $SE) {
        $data[] = [
            'id' => $SE->getId(),
            'type' => $SE->getType(),
            'title' => $SE->getTitle(),
            'description' => $SE->getDescription(),
            'startDate' => $SE->getStartDate()?->format('Y-m-d'), // null-safe
            'endDate' => $SE->getEndDate()?->format('Y-m-d'),
            'cv_id' => $SE->getCv()?->getId(),
            'student_id' => $SE->getCv()?->getStudent()?->getId(),
        ];
    }

    return new JsonResponse($data, 200);
}


#[Route('/{id}', name: 'get_section_entry', methods: ['GET'])]
#[OA\Get(
    path: '/api/section-entry/{id}',
    summary: 'Get a section entry by ID',
    tags: ['Section Entry'],
    parameters: [
        new OA\Parameter(
            name: 'id',
            in: 'path',
            required: true,
            schema: new OA\Schema(type: 'integer'),
            description: 'ID of the Section Entry'
        )
    ],
    responses: [
        new OA\Response(response: 200, description: 'section entry found'),
        new OA\Response(response: 404, description: 'section entry not found'),
    ]
)]
public function read(int $id, SectionEntryRepository $SERepo): JsonResponse
{
    $SE = $SERepo->find($id);

    if (!$SE) {
        return new JsonResponse(['message' => 'section entry not found'], 404);
    }

    return new JsonResponse([
        'id' => $SE->getId(),
        'type' => $SE->getType(),
        'title' => $SE->getTitle(),
        'description' => $SE->getDescription(),
        'startDate' => $SE->getStartDate()?->format('Y-m-d'),
        'endDate' => $SE->getEndDate()?->format('Y-m-d'),
        'cv_id' => $SE->getCv()?->getId(),
    ], 200);
}



}
