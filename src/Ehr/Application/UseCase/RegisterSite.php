<?php

namespace App\Ehr\Application\UseCase;

use App\Ehr\Application\Exception\InvalidSiteRegisterData;
use App\Ehr\Application\Port\EventBus;
use App\Ehr\Domain\Event\SiteRegistered;
use App\Ehr\Domain\Model\Site;
use App\Ehr\Domain\Repository\SiteRepository;

class RegisterSite
{
    public function __construct(
        private SiteRepository $siteRepository,
        private EventBus $eventBus
    ) {}

    /**
     * Register a new site in the platform
     *
     * @param string $name The site name
     * @param string $email The site contact email
     * @return string The generated site ID
     */
    public function execute(string $name, string $email): string
    {
        // Validate input
        if (empty($name) || empty($email)) {
            throw InvalidSiteRegisterData::forKeys($name, $email);
        }

        $siteId = $this->generateSiteId();
        $newSite = new Site(
            id: $siteId,
            name: $name,
            email: $email,
        );

        // Persist site (throws exception on failure)
        $this->siteRepository->save($newSite);

        // Publish domain event (only reached if save() succeeded)
        $this->eventBus->publish(new SiteRegistered(
            siteId: $siteId,
            email: $email
        ));

        // Return the generated site ID
        return $siteId;
    }

    private function generateSiteId(): string
    {
        // Using UUID v4 for unique site identification
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }
}