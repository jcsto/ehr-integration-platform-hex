<?php

namespace App\Controller;

use App\Ehr\Application\UseCase\SearchPatients;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class PatientController extends AbstractController 
{
    #[Route('ehr/{ehrClient}/patients', name: 'app_patient')]
    public function searchPatient(string $ehrClient, SearchPatients $searchPatients, Request $request): JsonResponse
    {
        $resultPatient = $searchPatients->execute(
            $ehrClient,
            $request->get('name', ''));

        $response = array_map(
            fn($item) => $item->toArray(), 
            $resultPatient
        );
        return $this->json($response);
    }
}
