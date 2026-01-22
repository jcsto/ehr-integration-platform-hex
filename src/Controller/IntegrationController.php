<?php

namespace App\Controller;

use App\Ehr\Application\UseCase\ConfigureIntegration;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class IntegrationController extends AbstractController
{
    #[Route(path: '/integration/configure', name: 'configure_integration', methods: ['POST'])]
    public function configure(ConfigureIntegration $configureIntegration, Request $request): JsonResponse
    {
        $body = $request->getContent();
        $data = json_decode($body, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \InvalidArgumentException('Invalid JSON body');
        }

        if (!isset($data['integrationId']) || !isset($data['integrationName'])) {
            throw new \InvalidArgumentException('Missing required fields: integrationId, integrationName');
        }

        $configureIntegration->execute(
            integrationId: $data['integrationId'],
            integrationName: $data['integrationName'],
            configuredBy: $data['configuredBy'] ?? 'system',
            configurationDetails: $data['configurationDetails'] ?? []
        );

        return $this->json([
            'message' => 'Integration configured successfully',
            'integrationId' => $data['integrationId']
        ], 201);
    }
}
