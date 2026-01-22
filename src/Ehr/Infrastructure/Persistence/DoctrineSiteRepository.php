<?php

namespace App\Ehr\Infrastructure\Persistence;

use App\Ehr\Domain\Model\Site;
use App\Ehr\Domain\Repository\SiteRepository;
use App\Ehr\Infrastructure\Persistence\Entity\SiteEntity;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class DoctrineSiteRepository implements SiteRepository
{

    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function save(Site $site): void
    {
        $entity = new SiteEntity(
            $site->id(),
            $site->name(),
            $site->email()
        );
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }
}