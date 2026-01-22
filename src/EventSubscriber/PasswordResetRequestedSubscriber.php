<?php

namespace App\EventSubscriber;

use App\Ehr\Domain\Event\PasswordResetRequested;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class PasswordResetRequestedSubscriber
{
    public function __construct(private LoggerInterface $logger)
    {
    }

    public function __invoke(PasswordResetRequested $event): void
    {
        $token = $this->generateToken();

        $this->logger->info('Password reset requested', [
            'userId' => $event->userId,
            'email' => $event->email,
            'token' => $token,
            'timestamp' => date('Y-m-d H:i:s')
        ]);

        // TODO: Store token in database with expiration
        // $this->tokenRepository->save($event->userId, $token, expiration: '+1 hour');

        $this->sendPasswordResetEmail($event->email, $token);
    }

    private function generateToken(): string
    {
        return bin2hex(random_bytes(32));
    }

    private function sendPasswordResetEmail(string $email, string $token): void
    {
        // Email stub - replace with real mailer in production
        $resetLink = "https://example.com/reset-password?token={$token}";

        $this->logger->info('STUB: Sending password reset email', [
            'to' => $email,
            'resetLink' => $resetLink
        ]);
    }
}
