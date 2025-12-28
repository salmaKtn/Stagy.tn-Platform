<?php

namespace App\Controller;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Entity\Company;
use App\Form\CompanyType;
use App\Repository\CompanyRepository;
use Doctrine\ORM\EntityManagerInterface;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/company', name: 'company_')]
class CompanyController extends AbstractController
{
     
#[Route('/offers', name: 'get_offers_by_company', methods: ['GET'])]
#[IsGranted('ROLE_COMPANY')]
public function getOffersByCompany(): JsonResponse
{
    /** @var \App\Entity\Company $company */
    $company = $this->getUser();

    $offers = $company->getOffers();

    $data = [];

    foreach ($offers as $offer) {
        $data[] = [
            'id' => $offer->getId(),
            'title' => $offer->getTitle(),
            'roles' => $offer->getRoles(),
            'workingHours' => $offer->getWorkingHours(),
            'payment' => $offer->getPayment(),
            'description' => $offer->getDescription(),
            'location' => $offer->getLocation(),
            'type' => $offer->getType(),
            'startDate' => $offer->getStartDate()?->format('Y-m-d'),
            'endDate' => $offer->getEndDate()?->format('Y-m-d'),
            'duration' => $offer->getDuration(),
            'category' => $offer->getCategory(),
            'governorate' => $offer->getGovernorate(),
            'image'=> $offer->getImage(),
        ];
    }

    return new JsonResponse($data, 200);
}

   
   

    #[Route('/{id}', name: 'update', methods: ['PUT'])]
    #[OA\Put(
        path: '/api/company/{id}',
        summary: 'Update an existing company',
        tags: ['Company'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer'),
                description: 'ID of the Company'
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/RegisterCompany')
        ),
        responses: [
            new OA\Response(response: 200, description: 'Company updated'),
            new OA\Response(response: 400, description: 'Validation error'),
            new OA\Response(response: 404, description: 'Company not found')
        ]
    )]
    public function update(int $id, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $company = $em->getRepository(Company::class)->find($id);
        if (!$company) {
            return new JsonResponse(['message' => 'Company not found'], 404);
        }

        $data = json_decode($request->getContent(), true);
        $form = $this->createForm(CompanyType::class, $company);
        $form->submit($data, false);

        if (!$form->isValid()) {
            $errors = [];
            foreach ($form->getErrors(true) as $error) {
                $errors[] = $error->getMessage();
            }
            return new JsonResponse(['errors' => $errors], 400);
        }

        $em->flush();

        return new JsonResponse(['message' => 'Company updated']);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    #[OA\Delete(
        path: '/api/company/{id}',
        summary: 'Delete an existing company',
        tags: ['Company'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer'),
                description: 'ID of the Company'
            )
        ],
        responses: [
            new OA\Response(response: 204, description: 'Company deleted'),
            new OA\Response(response: 404, description: 'Company not found')
        ]
    )]
    public function delete(int $id, CompanyRepository $CRepo, EntityManagerInterface $em): JsonResponse
    {
        $company = $CRepo->find($id);
        if (!$company) {
            return new JsonResponse(['message' => 'Company not found'], 404);
        }

        $em->remove($company);
        $em->flush();

        return new JsonResponse(['message' => 'Company deleted'], 204);
    }

    #[Route('', name: 'get_all_companies', methods: ['GET'])]
    #[OA\Get(
        path: '/api/company',
        summary: 'Get all companies',
        tags: ['Company'],
        responses: [
            new OA\Response(response: 200, description: 'List of companies'),
            new OA\Response(response: 204, description: 'No companies found'),
        ]
    )]
    public function readAll(CompanyRepository $CRepo): JsonResponse
    {
        $companies = $CRepo->findAll();
        if (!$companies) {
            return new JsonResponse(['message' => 'No companies found'], 204);
        }

        $data = [];
        foreach ($companies as $c) {
            $data[] = [
                'id' => $c->getId(),
                'username' => $c->getUsername(),
                'name' => $c->getName(),
                'location' => $c->getLocation(),
                'field'    => $c->getField()
            ];
        }

        return new JsonResponse($data, 200);
    }

    #[Route('/one', name: 'get_company_by_username', methods: ['GET'])]
    #[OA\Get(
        path: '/api/company/one',
        summary: 'Get company by ID',
        tags: ['Company'],
        responses: [
            new OA\Response(response: 200, description: 'Company found'),
            new OA\Response(response: 404, description: 'Company not found'),
        ]
    )]
    public function byusername( CompanyRepository $CRepo): JsonResponse
    {
      
         $user = $this->getUser();

   
    $username = $user->getUserIdentifier(); 

    $c = $CRepo->findOneBy(criteria: ['username' => $username]);

    if (!$c) {
        return new JsonResponse(['message' => 'Company not found'], 404);
    }
        return new JsonResponse([
            'id' => $c->getId(),
            'username' => $c->getUsername(),
            'name' => $c->getName(),
            'location' => $c->getLocation(),
            'logoURL' => $c->getLogoURL(),
            'coverURL' => $c->getCoverURL(),
            'slogan' => $c->getSlogan(),
            'about' => $c->getAbout(),
            'taxNumber' => $c->getTaxNumber(),
            'field' => $c->getField(),
            'socialMediaURLs' => $c->getSocialMediaURLs(),
            'agentName' => $c->getAgentName(),
            'agentLastname' => $c->getAgentLastname(),
            'agentEmail' => $c->getAgentEmail(),
            'validation' => $c->isValidation(),
        ], 200);
    }




    
#[Route('/by-id/{id}', name: 'get_company_by_id', methods: ['GET'])]
#[OA\Get(
    path: '/api/company/by-id/{id}',
    summary: 'Get company by id',
    tags: ['Company'],
    responses: [
        new OA\Response(response: 200, description: 'company found'),
        new OA\Response(response: 204, description: 'company not found'),
    ]
)]
public function byid(int $id,CompanyRepository $CRepo): JsonResponse
{
    $c = $CRepo->find($id);
     if (!$c) {
        return new JsonResponse(['message' => 'company not found'], 404);
    }
    return new JsonResponse([
         'id' => $c->getId(),
            'username' => $c->getUsername(),
            'name' => $c->getName(),
            'location' => $c->getLocation(),
            'logoURL' => $c->getLogoURL(),
            'coverURL' => $c->getCoverURL(),
            'slogan' => $c->getSlogan(),
            'about' => $c->getAbout(),
            'taxNumber' => $c->getTaxNumber(),
            'field' => $c->getField(),
            'socialMediaURLs' => $c->getSocialMediaURLs(),
            'agentName' => $c->getAgentName(),
            'agentLastname' => $c->getAgentLastname(),
            'agentEmail' => $c->getAgentEmail(),
            'validation' => $c->isValidation(),
              
    ],200);

    }


}
