<?php

namespace App\Controller;

use App\Entity\Student;
use App\Form\StudentType;
use App\Repository\StudentRepository;
use Doctrine\ORM\EntityManagerInterface;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/student', name:'student_')]
class StudentController extends AbstractController
{
    
  
    #[Route('/{id}', name: 'update', methods: ['PUT'])]
    #[OA\Put(
        path: '/api/student/{id}',
        summary: 'Update an existing student',
        tags: ['Student'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer'),
                description: 'ID of the Student'
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/RegisterStudent')
        ),
        responses: [
            new OA\Response(response: 200, description: 'Student updated'),
            new OA\Response(response: 400, description: 'Validation error'),
            new OA\Response(response: 404, description: 'Student not found')
        ]
    )]
    public function update(int $id, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $student = $em->getRepository(Student::class)->find($id);
        if (!$student) {
            return new JsonResponse(['message' => 'Student not found'], 404);
        }

        $data = json_decode($request->getContent(), true);

        $form = $this->createForm(StudentType::class, $student);
        $form->submit($data, false); 

        if (!$form->isValid()) {
            $errors = [];
            foreach ($form->getErrors(true) as $error) {
                $errors[] = $error->getMessage();
            }
            return new JsonResponse(['errors' => $errors], 400);
        }

        $em->flush();

        return new JsonResponse(['message' => 'Student updated']);
    }
      
    
    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    #[OA\Delete(
        path: '/api/student/{id}',
        summary: 'Delete an existing student',
        tags: ['Student'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer'),
                description: 'ID of the Student'
            )
        ],
       
        responses: [
            new OA\Response(response: 204, description: 'Student deleted'),
            new OA\Response(response: 404, description: 'Student not found')
        ]
    )]
    public function delete(int $id, StudentRepository $SRepo, EntityManagerInterface $em): JsonResponse
{
    $S = $SRepo->find($id);
    if (!$S) {
        return new JsonResponse(['message' => 'Student not found'], 404);
    }

    $em->remove($S);
    $em->flush();

    return new JsonResponse(['message' => 'Student deleted'], 204);
}

#[Route('', name: 'get_all_students', methods: ['GET'])]
#[OA\Get(
    path: '/api/student',
    summary: 'Get all students',
    tags: ['Student'],
    responses: [
        new OA\Response(response: 200, description: 'List of students'),
        new OA\Response(response: 204, description: 'No students found'),
    ]
)]
public function readAll(StudentRepository $SRepo): JsonResponse
{
    $Ss = $SRepo->findAll();
    $data = [];
    if(!$Ss)
        return new JsonResponse(['message' => 'no students found'],status:204);
    foreach ($Ss as $S) {
        $data[] = [
            'id' => $S->getId(),
            'username' => $S->getUsername(),
           
        ];
    }

    return new JsonResponse($data, 200);
}

#[Route('/{id}', name: 'get_student', methods: ['GET'])]
#[OA\Get(
    path: '/api/student/{id}',
    summary: 'Get student by id',
    tags: ['Student'],
    responses: [
        new OA\Response(response: 200, description: 'student found'),
        new OA\Response(response: 204, description: 'student not found'),
    ]
)]
public function read(int $id,StudentRepository $SRepo): JsonResponse
{
    $S = $SRepo->find($id);
     if (!$S) {
        return new JsonResponse(['message' => 'student not found'], 404);
    }
    return new JsonResponse([
        'id' => $S->getId(),
        'username' => $S->getUsername(),
        'lasttName' => $S->getlastName(),
        'firstName' => $S->getfirstName(),
        'level' => $S->getLevel(),
        'fieldOfStudy' => $S->getfieldOfStudy(),
        'gender' => $S->getGender(),
        'email' => $S->getEmail(),
        'URLs' => $S->getURLs(),
        'cvFile' => $S->getCvFile(),
        'cv' => $S->getCv(),
        'phoneNumber' => $S->getPhoneNumber(),
        'picture' => $S->getPicture(),        
    ],200);

    }


    
#[Route('/byUsername', name: 'get_student', methods: ['GET'])]
#[OA\Get(
    path: '/api/student/byUsername',
    summary: 'Get student by username',
    tags: ['Student'],
    responses: [
        new OA\Response(response: 200, description: 'student found'),
        new OA\Response(response: 204, description: 'student not found'),
    ]
)]
public function byusername(StudentRepository $SRepo): JsonResponse
{
    
         $user = $this->getUser();

   
    $username = $user->getUserIdentifier(); 

    $S = $SRepo->findOneBy(criteria: ['username' => $username]);
     if (!$S) {
        return new JsonResponse(['message' => 'student not found'], 404);
    }
    return new JsonResponse([
        'id' => $S->getId(),
        'username' => $S->getUsername(),
        'lasttName' => $S->getlastName(),
        'firstName' => $S->getfirstName(),
        'level' => $S->getLevel(),
        'fieldOfStudy' => $S->getfieldOfStudy(),
        'gender' => $S->getGender(),
        'email' => $S->getEmail(),
        'URLs' => $S->getURLs(),
        'cvFile' => $S->getCvFile(),
        'cv' => $S->getCv(),
        'phoneNumber' => $S->getPhoneNumber(),
        'picture' => $S->getPicture(),        
    ],200);

    }



} 
