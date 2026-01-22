<?php

namespace App\Ehr\Domain\Repository;

use App\Ehr\Domain\Model\User;

interface UserRepository
{
    public function findByEmail(string $email): ?User;
}
