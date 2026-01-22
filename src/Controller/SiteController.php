<?php

namespace App\Controller;

use App\Ehr\Application\UseCase\RegisterSite;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class SiteController extends AbstractController
{
   #[Route(path: '/site', name: 'create_site', methods: ['POST'])]
    public function createSite(RegisterSite $registerSite, Request $request)
    {
        $body = $request->getContent();
        $data = json_decode($body, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \InvalidArgumentException('Invalid JSON body');
        }
        if (!isset($data['name']) || !isset($data['email'])) {
            throw new \InvalidArgumentException('Missing required fields: name and email');
        }
        $data['email'] = trim($data['email']);
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Invalid email format');
        }
        $resultCreateSite = $registerSite->execute($data['name'], $data['email']);

        $response = [
            'siteId' => $resultCreateSite,
            'name' => $data['name'],
            'email' => $data['email'],
        ];
        return $this->json($response, 201);
    }
}
