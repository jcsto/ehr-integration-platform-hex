<?php

namespace App\Ehr\Domain\Event;

final class IntegrationConfigured
{
    public function __construct(
        public readonly string $integrationId,
        public readonly string $integrationName,
        public readonly string $configuredByUserId
    ) {}
}
