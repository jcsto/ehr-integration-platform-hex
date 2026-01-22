<?php

namespace App\Ehr\Infrastructure\Epic;

use App\Ehr\Application\DTO\PatientDTO;
use App\Ehr\Domain\Port\EhrClientInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;


class EpicFhirClient implements EhrClientInterface
{
    private string $baseUrl;
    private string $tokenUrl;
    private string $clientId;
    private string $clientSecret;
    private ?string $accessToken = null;

    public function __construct(
        private ParameterBagInterface $parameterBagInterface,
        private HttpClientInterface $httpClient,
        string $baseUrl,
        string $tokenUrl,
        string $clientId,
        string $clientSecret
    ) {
        $this->parameterBagInterface = $parameterBagInterface;
        $this->httpClient = $httpClient;
        $this->baseUrl = $baseUrl;
        $this->tokenUrl = $tokenUrl;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
    }

    private function getAccessToken(): string
    {
        if ($this->accessToken !== null) {
            return $this->accessToken;
        }

        $response = $this->httpClient->request('POST', $this->tokenUrl, [
            'auth_basic' => [$this->clientId, $this->clientSecret],
            'body' => ['grant_type' => 'client_credentials'],
        ]);

        $data = $response->toArray();
        $this->accessToken = $data['access_token'];

        return $this->accessToken;
    }

    private function makeRequest(string $method, string $endpoint, array $options = []): array
    {
        $token = $this->getAccessToken();
        $options['headers'] = array_merge($options['headers'] ?? [], [
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/fhir+json',
        ]);

        $response = $this->httpClient->request($method, $this->baseUrl . $endpoint, $options);

        return $response->toArray();
    }

    public function getPatient(string $patientId): array
    {
        return $this->makeRequest('GET', "/Patient/{$patientId}");
    }

    /**
     * Get a list of patients from the HTTP query param
     * 
     * @param string $query
     * @return PatientDTO[]
     */
    public function searchPatients(string $query): array
    {
        // get data from the EHR
        // $data = $this->makeRequest('GET', "/STU3/Patient?name={$query}");

        // mock test data
        $json = file_get_contents(
        $this->parameterBagInterface->get('kernel.project_dir') . '/fixtures/ehr/Epic/patient.search.response.json'
        );
        $data = json_decode($json, true);
        
        $patients = [];
        foreach ($data["entry"] as $patient) {

            $name = $patient['resource']['name'][0] ?? [];
            $given = $name['given'][0] ?? '';
            $family = $name['family'] ?? '';
            $fullName = trim($given . ' ' . $family);

            $patients[] = new PatientDTO(
                $patient['resource']['id'],
                $fullName,
                dob: $patient['resource']['birthDate']
            );
        }
        return $patients;
    }

}