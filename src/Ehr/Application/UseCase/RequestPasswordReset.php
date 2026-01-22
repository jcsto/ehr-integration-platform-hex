<?php

namespace App\Ehr\Application\UseCase;

use App\Ehr\Application\Port\EventBus;
use App\Ehr\Domain\Event\PasswordResetRequested;
use App\Ehr\Domain\Repository\UserRepository;

class RequestPasswordReset
{
    public function __construct(
        private UserRepository $userRepository,
        private EventBus $eventBus
    ) {}

    public function execute(string $email): void
    {
        $user = $this->userRepository->findByEmail($email);

        if ($user === null) {
            // Silent fail for security - don't reveal if email exists
            return;
        }

        $this->eventBus->publish(new PasswordResetRequested(
            userId: $user->id(),
            email: $user->email()
        ));
    }
}
