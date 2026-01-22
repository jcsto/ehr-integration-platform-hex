<?php

namespace App\EventSubscriber;

use App\Ehr\Domain\Event\SiteRegistered;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Mime\Email;

#[AsMessageHandler]
class SiteRegisteredSubscriber
{
   public function __construct(private LoggerInterface $logger) {}

   public function __invoke(SiteRegistered $event): void
   {
      $this->logger->info('🎉 Site registered successfully!', [
         'siteId' => $event->siteId,
         'email' => $event->email,
         'timestamp' => date('Y-m-d H:i:s')
      ]);

      $email = (new Email())
         ->from('hello@example.com')
         ->to('you@example.com')
         ->subject('Time for Symfony Mailer!')
         ->text('Sending emails is fun again!')
         ->html('<p>See Twig integration for better HTML integration!</p>');

      $mailer->send($email);
   }
}
