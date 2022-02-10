<?php

namespace App\Controller;

use App\Repository\AutomobilesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class ApiController extends AbstractController
{
    /**
     * @param \App\Repository\AutomobilesRepository $automobilesRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    #[Route('/api/v1/autos', name: 'api_show_autos', methods: 'GET')]
    public function autos(AutomobilesRepository $automobilesRepository): Response
    {
       return $this->json(['autos' => $automobilesRepository->findAutosAPI()], 200, [], ['groups' => 'autos:api'])->setEncodingOptions(JSON_PRETTY_PRINT);
    }

    /**
     * @param \App\Repository\AutomobilesRepository $automobilesRepository
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    #[Route('/api/v1/auto/{id}', name: 'api_show_auto', requirements: ["id" => "\d+"], methods: 'GET')]
    public function auto(AutomobilesRepository $automobilesRepository, int $id): JsonResponse
    {
      return $this->json(['autos' => $automobilesRepository->findAutosAPIById($id)], 200, [], ['groups' => 'auto:api'])->setEncodingOptions(JSON_PRETTY_PRINT);

    }
}
