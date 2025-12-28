<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\Student;
use App\Form\StudentType;
use App\Form\CompanyType;
use OpenApi\Attributes as OA;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

use \DateTime;
#[Route('/api/register', name: 'registration_')]
class RegistrationController extends AbstractController
{
    #[Route('/student', name: 'register_student', methods: ['POST'])]
    #[OA\Post(
        path: '/api/register/student',
        summary: 'Register a new Student',
        tags: ['Registration'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/RegisterStudent')
        ),
        responses: [
            new OA\Response(response: 201, description: 'Student successfully registered'),
            new OA\Response(response: 400, description: 'Validation error')
        ]
    )]    
    public function registerStudent(Request $request,EntityManagerInterface $em,UserPasswordHasherInterface $passwordHasher,): JsonResponse {

        $student = new Student();
        $data = json_decode($request->getContent(), true);
        $form = $this->createForm(StudentType::class, $student);
        $form->submit($data, false);


        $student->setBirthady(new DateTime($data['birthday']));
        $student->setRoles(['ROLE_STUDENT']);
        $hashedPassword = $passwordHasher->hashPassword($student, $student->getPassword());
        $student->setPassword($hashedPassword);



        $em->persist($student);
        $em->flush();

        return new JsonResponse(['message' => 'Student created', 'id' => $student->getId()], 201);

    
}

    #[Route('/company', name: 'register_company', methods: ['POST'])]
    #[OA\Post(
        path: '/api/register/company',
        summary: 'Register a new company',
        tags: ['Registration'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/RegisterCompany')
        ),
        responses: [
            new OA\Response(response: 201, description: 'Company successfully registered'),
            new OA\Response(response: 400, description: 'Validation error')
        ]
    )]  
    public function registerCompany(Request $request,EntityManagerInterface $em,UserPasswordHasherInterface $passwordHasher,): JsonResponse 
    { 
        $company = new Company();
        $data = json_decode($request->getContent(), true);
        $form = $this->createForm(CompanyType::class, $company);
        $form->submit($data);

        if (!$form->isValid()) {
            $errors = [];
            foreach ($form->getErrors(true) as $error) {
                $errors[] = $error->getMessage();
            }
            return new JsonResponse(['errors' => $errors], 400);
        }

       
        $company->setRoles(['ROLE_COMPANY']);
        $hashedPassword = $passwordHasher->hashPassword($company, $data['password']);
        $company->setPassword($hashedPassword);

        $em->persist($company);
        $em->flush();

        return new JsonResponse(['message' => 'Company created', 'id' => $company->getId()], 201);
    
    }

}
