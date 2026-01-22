<?php

namespace App\Ehr\Application\UseCase;

use App\Ehr\Application\Port\EventBus;
use App\Ehr\Domain\Event\IntegrationConfigured;
use App\Ehr\Domain\Model\Integration;
use App\Ehr\Domain\Repository\IntegrationRepository;

class ConfigureIntegration
{
    public function __construct(
        private EventBus $eventBus,
        private IntegrationRepository $integrationRepository
    ) {}

    public function execute(
        string $integrationId,
        string $integrationName,
        string $configuredByUserId,
        string $description = '',
        array $configurationDetails = []
    ): Integration {
        $integration = new Integration(
            id: $integrationId,
            name: $integrationName,
            description: $description,
            status: 'configured'
        );

        $this->integrationRepository->save($integration);
        $this->eventBus->publish(new IntegrationConfigured(
            integrationId: $integrationId,
            integrationName: $integrationName,
            configuredByUserId: $configuredByUserId
        ));

        return $integration;
    }
}
