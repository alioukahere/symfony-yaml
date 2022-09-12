<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    denormalizationContext: [
        'groups' => [
            'organization:create',
        ],
    ],
    normalizationContext: [
        'groups' => [
            'organization:read',
        ],
    ],
    itemOperations: [
        'get',
        'patch',
        'delete',
    ]
)]
class Organization
{
    #[ApiProperty(identifier: true)]
    #[Groups([
        'organization:create',
        'organization:read',
    ])]
    private string $name;

    #[Groups([
        'organization:create',
        'organization:read',
    ])]
    private ?string $description = null;

    #[Groups([
        'organization:create',
        'organization:read',
    ])]
    private Collection $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): void
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
        }
    }

    public function removeUser(User $user): void
    {
        $this->users->removeElement($user);
    }
}
