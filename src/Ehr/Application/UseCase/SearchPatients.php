<?php

namespace App\Ehr\Application\UseCase;

class SearchPatients extends BaseUseCase
{

    /**
     * Execute patient search
     * 
     * @param string $ehrKey The EHR client key ("epic", "athena", etc.)
     * @param string $query The HTTP query parameter for searching patients
     * @return array
     */
    public function execute(string $ehrKey, string $query): array
    {
        return $this->client($ehrKey)->searchPatients($query);
    }
}