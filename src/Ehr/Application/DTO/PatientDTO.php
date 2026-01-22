<?php

namespace App\Ehr\Application\DTO;

final class PatientDTO
{
    public function __construct(
        public readonly string $patientId,
        public readonly string $name,
        public readonly string $dob,
    ) {}

    public function toArray(): array
    {
        return [
            'patientId' => $this->patientId,
            'name' => $this->name,
            'dob' => $this->dob,
        ];
    }
}
