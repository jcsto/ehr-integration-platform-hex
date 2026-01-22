<?php

namespace App\Ehr\Application\DTO;

class PatientsSearchResultDTO
{
    private array $patients;

    public function __construct(array $patients)
    {
        $this->patients = $patients;
    }

    public function getPatients(): array
    {
        return $this->patients;
    }

    public function toArray(): array
    {
        return [
            'patients' => array_map(
                fn ($p) => $p->toArray(),
                $this->patients
            ),
        ];
    }
}