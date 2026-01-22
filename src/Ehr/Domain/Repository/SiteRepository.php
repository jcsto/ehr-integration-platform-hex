<?php

namespace App\Ehr\Domain\Repository;

use App\Ehr\Domain\Model\Site;

interface SiteRepository
{
    public function save(Site $site): void;
}
