<?php

namespace App\Controller;


use App\Entity\CV;
use App\Repository\CVRepository;
use App\Form\CVType;
use App\Entity\Student;
use App\Entity\SectionEntry;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\JsonRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use OpenApi\Attributes as OA;
use Nelmio\ApiDocBundle\Attribute\Model;

#[Route('/api/cv', name: 'cv')]
class CVController extends AbstractController
{
   #[Route('', name: 'create_cv', methods: ['POST'])]
   #[OA\Post(
    path: '/api/cv',
    summary: 'Create a new CV',
    tags: ['CV'],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(
                    property: 'student',
                    type: 'integer',
                    description: 'ID of an existing student',
                    example: 1
                ),
            ],
            required: ['student']
        )
    ),
    responses: [
        new OA\Response(response: 201, description: 'CV created'),
        new OA\Response(response: 400, description: 'Validation error'),
    ]
)]
public function create(Request $request, EntityManagerInterface $em, ValidatorInterface $validator): JsonResponse
{
    $cv = new CV();

    // Decode JSON and submit to Form
    $data = json_decode($request->getContent(), true);
    $form = $this->createForm(CVType::class, $cv);
    $form->submit($data);

    // Validate the form
    if (!$form->isValid()) {
        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[] = $error->getMessage();
        }
        return new JsonResponse(['errors' => $errors], 400);
    }

    $em->persist($cv);
    $em->flush();

    return new JsonResponse([
        'message' => 'CV created',
        'id' => $cv->getId()
    ], 201);
}


#[Route('/{id}', name: 'get_cv', methods: ['GET'])]
#[OA\Get(
    path: '/api/cv/{id}',
    summary: 'Get a CV by ID',
    tags: ['CV'],
    parameters: [
        new OA\Parameter(
            name: 'id',
            in: 'path',
            required: true,
            schema: new OA\Schema(type: 'integer'),
            description: 'CV ID'
        )
    ],
    responses: [
        new OA\Response(response: 200, description: 'CV found'),
        new OA\Response(response: 404, description: 'CV not found'),
    ]
)]
public function read(int $id, CVRepository $cvRepo): JsonResponse
{
    $cv = $cvRepo->find($id);

    if (!$cv) {
        return new JsonResponse(['message' => 'CV not found'], 404);
    }

    return new JsonResponse([
        'id' => $cv->getId(),
        'student' => $cv->getStudent()->getId(),
    ], 200);
}
#[Route('', name: 'list_cvs', methods: ['GET'])]
#[OA\Get(
    path: '/api/cv',
    summary: 'Get all CVs',
    tags: ['CV'],
    responses: [
        new OA\Response(response: 200, description: 'List of CVs'),
    ]
)]
public function readAll(CVRepository $cvRepo): JsonResponse
{
    $cvs = $cvRepo->findAll();
    $data = [];

    foreach ($cvs as $cv) {
        $data[] = [
            'id' => $cv->getId(),
            'student' => $cv->getStudent()->getId(),
        ];
    }

    return new JsonResponse($data, 200);
}
#[Route('/{id}', name: 'update_cv', methods: ['PUT'])]
#[OA\Put(
    path: '/api/cv/{id}',
    summary: 'Update a CV',
    tags: ['CV'],
    parameters: [
        new OA\Parameter(
            name: 'id',
            in: 'path',
            required: true,
            schema: new OA\Schema(type: 'integer'),
            description: 'CV ID'
        )
    ],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(
                    property: 'student',
                    type: 'integer',
                    description: 'ID of an existing student',
                    example: 2
                ),
            ],
            required: ['student']
        )
    ),
    responses: [
        new OA\Response(response: 200, description: 'CV updated'),
        new OA\Response(response: 400, description: 'Validation error'),
        new OA\Response(response: 404, description: 'CV not found'),
    ]
)]
public function update(
    int $id,
    Request $request,
    EntityManagerInterface $em,
    CVRepository $cvRepo
): JsonResponse {
    $cv = $cvRepo->find($id);
    if (!$cv) {
        return new JsonResponse(['message' => 'CV not found'], 404);
    }

    $data = json_decode($request->getContent(), true);
    $form = $this->createForm(CVType::class, $cv);
    $form->submit($data);

    if (!$form->isValid()) {
        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[] = $error->getMessage();
        }
        return new JsonResponse(['errors' => $errors], 400);
    }

    $em->flush();

    return new JsonResponse(['message' => 'CV updated'], 200);
}
#[Route('/{id}', name: 'delete_cv', methods: ['DELETE'])]
#[OA\Delete(
    path: '/api/cv/{id}',
    summary: 'Delete a CV',
    tags: ['CV'],
    parameters: [
        new OA\Parameter(
            name: 'id',
            in: 'path',
            required: true,
            schema: new OA\Schema(type: 'integer'),
            description: 'CV ID'
        )
    ],
    responses: [
        new OA\Response(response: 204, description: 'CV deleted'),
        new OA\Response(response: 404, description: 'CV not found'),
    ]
)]
public function delete(int $id, CVRepository $cvRepo, EntityManagerInterface $em): JsonResponse
{
    $cv = $cvRepo->find($id);
    if (!$cv) {
        return new JsonResponse(['message' => 'CV not found'], 404);
    }

    $em->remove($cv);
    $em->flush();

    return new JsonResponse(null, 204);
}

}
