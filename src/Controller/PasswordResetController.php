<?php

namespace App\Controller;

use App\Ehr\Application\UseCase\RequestPasswordReset;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Target;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Symfony\Component\RateLimiter\RateLimiterFactory;
use Symfony\Component\Routing\Attribute\Route;

final class PasswordResetController extends AbstractController
{
    #[Route(path: '/password-reset', name: 'request_password_reset', methods: ['POST'])]
    public function requestReset(
        RequestPasswordReset $requestPasswordReset, 
        Request $request, 
        #[Target('anonymous_api.limiter')] RateLimiterFactory $anonymousApiLimiter
    ): JsonResponse
    {

        #Source: https://symfony.com/doc/6.4/rate_limiter.html#using-the-rate-limiter-service
        // Apply rate limiting to prevent abuse per IP
        $limiter = $anonymousApiLimiter->create($request->getClientIp());
        if (false === $limiter->consume(1)->isAccepted()) {
            throw new TooManyRequestsHttpException();
        }

        $body = $request->getContent();
        $data = json_decode($body, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \InvalidArgumentException('Invalid JSON body');
        }

        if (!isset($data['email'])) {
            throw new \InvalidArgumentException('Missing required field: email');
        }

        $email = trim($data['email']);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Invalid email format');
        }

        $requestPasswordReset->execute($email);

        // Always return success for security (don't reveal if email exists)
        return $this->json([
            'message' => 'If the email exists, a password reset link will be sent.'
        ]);
    }
}
