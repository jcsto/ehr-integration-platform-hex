<?php

namespace App\Ehr\Application\Exception;

final class UnknownEhrClient extends \RuntimeException
{
    public static function forKey(string $key): self
    {
        return new self("EHR client not found: {$key}");
    }
}
