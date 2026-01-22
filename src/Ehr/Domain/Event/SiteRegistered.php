<?php

namespace App\Ehr\Domain\Event;

final class SiteRegistered
{
    public function __construct(
        public readonly string $siteId,
        public readonly string $email
    ) {}
}
