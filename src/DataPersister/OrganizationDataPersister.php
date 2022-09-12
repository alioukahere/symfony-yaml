<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Organization;
use App\Repository\OrganizationRepository;

class OrganizationDataPersister implements
    ContextAwareDataPersisterInterface
{
    public function __construct(
        private readonly OrganizationRepository $organizationRepository
    ) {
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof Organization;
    }

    public function persist($data, array $context = [])
    {
    }

    public function remove($data, array $context = [])
    {
        $this->organizationRepository->remove($data);
    }
}
