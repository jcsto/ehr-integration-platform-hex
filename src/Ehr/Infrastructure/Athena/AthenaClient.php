<?php

namespace App\Ehr\Infrastructure\Athena;

use App\Ehr\Application\DTO\PatientDTO;
use App\Ehr\Domain\Port\EhrClientInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;


class AthenaClient implements EhrClientInterface
{
    private string $baseUrl;
    private string $tokenUrl;
    private string $clientId;
    private string $clientSecret;
    private string $clientPractice;
    private string $clientScopes;
    private ?string $accessToken = null;

    public function __construct(
        private ParameterBagInterface $parameterBagInterface,
        private HttpClientInterface $httpClient,
        string $baseUrl,
        string $tokenUrl,
        string $clientId,
        string $clientSecret,
        string $clientPractice,
        string $clientScopes,
    ) {
        $this->parameterBagInterface = $parameterBagInterface;
        $this->httpClient = $httpClient;
        $this->baseUrl = $baseUrl;
        $this->tokenUrl = $tokenUrl;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->clientPractice = $clientPractice;
        $this->clientScopes = $clientScopes;
    }

    /**
     * Uses 2-legged Token Generation
     * Response e.g.:
     *      {
     *        "access_token": "bSQeVaRd4...........",
     *        "expires_in": "300"
     *      }
     * @return string|null
     */
    private function getAccessToken(): string
    {
        if ($this->accessToken !== null) {
            return $this->accessToken;
        }

        $response = $this->httpClient->request('POST', $this->tokenUrl, [
            'auth_basic' => [$this->clientId, $this->clientSecret],
            'body' => ['grant_type' => 'client_credentials', 'scopes' => $this->clientScopes],
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
        // $data = $this->makeRequest('GET', "/{$this->clientPractice}/patients?firstname={$query['firstname']}&dob={mm%2Fdd%2Fyyyy}&lastname={lastName}");

        // mock test data
        $json = file_get_contents(
        $this->parameterBagInterface->get('kernel.project_dir') . '/fixtures/ehr/Athena/patient.search.response.json'
        );
        $data = json_decode($json, true);
        
        $patients = [];
        foreach ($data["patients"] as $patient) {

            $patients[] = new PatientDTO(
                $patient['patientid'],
                $patient['firstname'] . ' ' . $patient['lastname'],
                dob: $patient['dob']
            );
        }
        return $patients;
    }

}