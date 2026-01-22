<?php

namespace App\Ehr\Domain\Model;

class Integration
{
    private string $id;
    private string $name;
    private string $description;
    private string $status;

    public function __construct(
        string $id,
        string $name,
        string $description,
        string $status
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->status = $status;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getStatus(): string
    {
        return $this->status;
    }
}