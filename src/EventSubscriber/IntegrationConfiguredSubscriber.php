<?php

namespace App\EventSubscriber;

use App\Ehr\Domain\Event\IntegrationConfigured;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class IntegrationConfiguredSubscriber
{
    public function __construct(private LoggerInterface $logger)
    {
    }

    public function __invoke(IntegrationConfigured $event): void
    {
        $this->writeAuditLog($event);
    }

    private function writeAuditLog(IntegrationConfigured $event): void
    {
        $this->logger->info('AUDIT: Integration configured', [
            'action' => 'INTEGRATION_CONFIGURED',
            'integrationId' => $event->integrationId,
            'integrationName' => $event->integrationName,
            'configuredBy' => $event->configuredBy,
            'configurationDetails' => $event->configurationDetails,
            'timestamp' => date('Y-m-d H:i:s'),
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
        ]);

        // TODO: Persist audit log to database
        // $this->auditLogRepository->save(new AuditLog(...));
    }
}
