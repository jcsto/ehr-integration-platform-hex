<?php

namespace App\Ehr\Application\UseCase;

use App\Ehr\Application\EhrClientResolver;
use App\Ehr\Domain\Port\EhrClientInterface;

abstract class BaseUseCase
{
    public function __construct(protected EhrClientResolver $resolver) {}

    protected function client(string $ehrKey): EhrClientInterface
    {
        return $this->resolver->resolve($ehrKey);
    }
}
