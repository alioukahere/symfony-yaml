<?php

namespace App\Entity;

use ApiPlatform\Core\Action\NotFoundAction;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    collectionOperations: [],
    itemOperations: [
        'get' => [
            'controller' => NotFoundAction::class,
        ],
    ]
)]
class User
{
    #[ApiProperty(identifier: true)]
    #[Groups([
        'organization:create',
        'organization:read',
    ])]
    private ?string $name = null;

    #[Groups([
        'organization:create',
        'organization:read',
    ])]
    private array $role = [];

    #[Groups([
        'organization:create',
        'organization:read',
    ])]
    private ?string $password = null;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getRole(): array
    {
        return $this->role;
    }

    public function setRole(array $role): void
    {
        $this->role = $role;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }
}
