# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

EHR Integration Platform - A Symfony 6.4 application implementing Hexagonal Architecture for integrating multiple healthcare EHR systems (Epic FHIR and Athena Health APIs).

## Development Commands

```bash
# Install dependencies
composer install

# Run with Docker (full stack: PHP, Nginx, MySQL, RabbitMQ, MailHog)
cd docker && docker-compose up -d

# Clear cache
php bin/console cache:clear

# List available routes
php bin/console debug:router
```

**Access Points (Docker):**
- Web: http://localhost:8080
- RabbitMQ Management: http://localhost:15672 (guest/guest)
- MailHog: http://localhost:8025
- MySQL: localhost:3307 (app/app)

## Architecture

This project uses **Hexagonal Architecture (Ports & Adapters)** with Domain-Driven Design:

```
src/Ehr/
├── Domain/           # Pure business logic, no framework dependencies
│   ├── Model/        # Entities: Site, User, Integration
│   ├── Port/         # Interfaces: EhrClientInterface
│   ├── Event/        # Domain events: SiteRegistered
│   └── Repository/   # Repository interfaces: SiteRepository
├── Application/      # Use cases and orchestration
│   ├── UseCase/      # SearchPatients, RegisterSite (extend BaseUseCase)
│   ├── DTO/          # PatientDTO, PatientsSearchResultDTO
│   ├── Port/         # EventBus interface
│   └── EhrClientResolver.php  # Service locator for EHR clients
└── Infrastructure/   # External implementations
    ├── Epic/         # EpicFhirClient
    ├── Athena/       # AthenaClient
    ├── Messaging/    # SymfonyEventBus
    └── Persistence/  # DoctrineSiteRepository
```

**Key Pattern:** EHR clients are tagged with `app.ehr_client` in `config/services.yaml` and resolved dynamically via `EhrClientResolver` using a service locator.

## Adding a New EHR Integration

1. Create client class in `src/Ehr/Infrastructure/{Vendor}/` implementing `EhrClientInterface`
2. Register in `config/services.yaml` with the `app.ehr_client` tag:
   ```yaml
   App\Ehr\Infrastructure\NewVendor\NewVendorClient:
       tags:
           - { name: 'app.ehr_client', key: 'newvendor' }
       arguments:
           $baseUrl: '%env(NEWVENDOR_BASE_URL)%'
   ```
3. Add environment variables to `.env`
4. Access via endpoint: `GET /ehr/newvendor/patients?name=query`

## Adding a New Use Case

1. Create class extending `BaseUseCase` in `src/Ehr/Application/UseCase/`
2. Use `$this->client($ehrKey)` to get the EHR client dynamically
3. Inject into controller and expose via HTTP endpoint

## API Endpoints

- `GET /` - Home page
- `GET /callback` - OAuth callback
- `GET /ehr/{ehrClient}/patients?name=query` - Search patients (ehrClient: epic, athena)
- `POST /site` - Register site (incomplete)

## Environment Variables

EHR credentials are configured in `.env`:
- `EPIC_CLIENT_ID`, `EPIC_CLIENT_SECRET`
- `ATHENA_CLIENT_ID`, `ATHENA_CLIENT_SECRET`, `ATHENA_CLIENT_PRACTICE`, `ATHENA_CLIENT_SCOPES`

## Key Constraints

- **Domain layer** (`src/Ehr/Domain/`) must never depend on Symfony or external libraries
- **EHR clients** must implement `EhrClientInterface` and be resolved through `EhrClientResolver`
- **Events** are dispatched via `EventBus` port, handled by subscribers in `src/EventSubscriber/`
- **Fixtures** in `fixtures/ehr/` contain mock API responses for development
