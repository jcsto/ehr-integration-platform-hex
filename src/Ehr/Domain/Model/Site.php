<?php

namespace App\Ehr\Domain\Model;

final class Site
{
    public function __construct(
        private string $id,
        private string $name,
        private string $email
    ) {}

    public function id(): string
    {
        return $this->id;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function isActive(): bool {
        return true;
    }

    public function changeEmail(string $newEmail): void {
        $this->email = $newEmail;
    }

}
