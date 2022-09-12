<?php

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\Organization;
use App\Repository\OrganizationRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OrganizationDataProvider implements
    ContextAwareCollectionDataProviderInterface,
    ItemDataProviderInterface,
    RestrictedDataProviderInterface
{
    public function __construct(
        private readonly OrganizationRepository $organizationRepository
    ) {
    }

    public function supports(
        string $resourceClass,
        ?string $operationName = null,
        array $context = []
    ): bool {
        return Organization::class === $resourceClass;
    }

    public function getCollection(
        string $resourceClass,
        ?string $operationName = null,
        array $context = []
    ): iterable {
        return $this->organizationRepository->getAll();
    }

    public function getItem(
        string $resourceClass,
        $id,
        ?string $operationName = null,
        array $context = []
    ): Organization {
        $organization = $this->organizationRepository->getItem($id);
        if (!$organization instanceof Organization) {
            throw new NotFoundHttpException(
                "Organization with name {$id} not found."
            );
        }

        return $organization;
    }
}
