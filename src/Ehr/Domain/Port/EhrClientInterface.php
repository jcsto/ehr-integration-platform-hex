<?php

namespace App\Ehr\Domain\Port;

interface EhrClientInterface
{

    /**
     * Retrieve patient data from the EHR system
     *
     * @param string $patientId
     * @return array
     */
    public function getPatient(string $patientId): array;

    /**
     * Search patients by query
     *
     * @param string $query
     * @return array
     */
    public function searchPatients(string $query): array;

}