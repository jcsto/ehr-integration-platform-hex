<?php

namespace App\Ehr\Infrastructure\Persistence;

use App\Ehr\Domain\Model\Integration;
use App\Ehr\Domain\Repository\IntegrationRepository;
use App\Ehr\Infrastructure\Persistence\Entity\IntegrationEntity;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineIntegrationRepository implements IntegrationRepository
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function save(Integration $integration): void
    {
        $entity = new IntegrationEntity(
            $integration->getId(),
            $integration->getName(),
            $integration->getDescription(),
            $integration->getStatus()
        );
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    public function findById(string $id): ?Integration
    {
        return $this->entityManager->getRepository(IntegrationEntity::class)->find($id);
    }

    public function findAll(): array
    {
        return $this->entityManager->getRepository(IntegrationEntity::class)->findAll();
    }
}