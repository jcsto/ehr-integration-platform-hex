<?php

namespace App\Ehr\Application;

use App\Ehr\Application\Exception\UnknownEhrClient;
use App\Ehr\Domain\Port\EhrClientInterface;
use Psr\Container\ContainerInterface;

final class EhrClientResolver
{
    public function __construct(
       private ContainerInterface $clientsLocator
    ) {
    }

    public function resolve(string $key): mixed
    {
        $key = strtolower($key);

        if (!$this->clientsLocator->has($key)) {
            throw UnknownEhrClient::forKey($key);
        }

        $client = $this->clientsLocator->get($key);

        // wrong service type
        if (!$client instanceof EhrClientInterface) {
            throw new \LogicException("Service tagged as app.ehr_client must implement EhrClientInterface: {$key}");
        }

        return $client;
    }
}