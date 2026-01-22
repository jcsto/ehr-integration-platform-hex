<?php

namespace App\Ehr\Domain\Repository;

use App\Ehr\Domain\Model\Integration;

interface IntegrationRepository
{
    public function save(Integration $integration): void;
    public function findById(string $id): ?Integration;
    public function findAll(): array;
}