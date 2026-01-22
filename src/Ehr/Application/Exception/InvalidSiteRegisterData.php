<?php

namespace App\Ehr\Application\Exception;

final class InvalidSiteRegisterData extends \RuntimeException
{
    public static function forKeys(string $name, string $email): self
    {
        return new self("Invalid name or email: {$name}, {$email}");
    }
}
