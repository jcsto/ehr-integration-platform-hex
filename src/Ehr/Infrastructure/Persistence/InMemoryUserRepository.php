<?php

namespace App\Ehr\Infrastructure\Persistence;

use App\Ehr\Domain\Model\User;
use App\Ehr\Domain\Repository\UserRepository;

class InMemoryUserRepository implements UserRepository
{
    /** @var array<string, User> */
    private array $users = [];

    public function __construct()
    {
        // Seed with test user
        $this->users['test@example.com'] = new User(
            id: 'user-001',
            name: 'Test User',
            email: 'test@example.com'
        );
    }

    public function findByEmail(string $email): ?User
    {
        return $this->users[$email] ?? null;
    }
}
