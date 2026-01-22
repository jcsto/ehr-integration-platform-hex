<?php

namespace App\Ehr\Domain\Event;

final class PasswordResetRequested
{
    public function __construct(
        public readonly string $userId,
        public readonly string $email
    ) {}
}
