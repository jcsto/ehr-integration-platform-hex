<?php

namespace App\EventSubscriber;

use App\Ehr\Application\Exception\UnknownEhrClient;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class ExceptionSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::EXCEPTION => 'onException'];
    }

    public function onException(ExceptionEvent $event): void
    {
        $e = $event->getThrowable();

        if ($e instanceof UnknownEhrClient) {
            $event->setResponse(new JsonResponse(
                ['error' => $e->getMessage()], 
                400)
            );
        }
    }
}
