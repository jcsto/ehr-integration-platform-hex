<?php

namespace App\Ehr\Infrastructure\Messaging;

use App\Ehr\Application\Port\EventBus;
use Symfony\Component\Messenger\MessageBusInterface;

class SymfonyEventBus implements EventBus
{

   public function __construct(private MessageBusInterface $bus)
   {
   }

   public function publish(object $event): void
	{
      $this->bus->dispatch($event);
	}
}
