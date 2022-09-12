<?php

namespace App\Repository;

use App\Entity\Organization;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Yaml\Yaml;
use Throwable;
use UnexpectedValueException;

/**
 * @extends ServiceEntityRepository<Organization>
 *
 * @method Organization|null find($id, $lockMode = null, $lockVersion = null)
 * @method Organization|null findOneBy(array $criteria, array $orderBy = null)
 * @method Organization[]    findAll()
 * @method Organization[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrganizationRepository
{
    public function __construct(
        private readonly string $yamlFilePath,
        private readonly SerializerInterface $serializer
    ) {
    }

    public function getAll(): array
    {
        try {
            $organizations = Yaml::parseFile($this->yamlFilePath);
        } catch (Throwable $exception) {
            throw new UnexpectedValueException(
                "Failed parsing YAML file {$this->yamlFilePath}"
            );
        }

        $organizations = $organizations['organizations'] ?? null;
        if (null === $organizations) {
            throw new UnexpectedValueException('Failed getting organizations');
        }

        $data = [];
        foreach ($organizations as $organization) {
            $data[] = $this->serializer->deserialize(
                json_encode($organization),
                Organization::class,
                'json'
            );
        }

        return $data;
    }

    public function getItem(string $name): ?Organization
    {
        $organizations = $this->getAll();

        $search = array_filter(
            $organizations,
            fn (Organization $data) => $data->getName() === $name
        );
        if (empty($search)) {
            return null;
        }

        return array_values($search)[0];
    }

    public function add(Organization $entity, bool $flush = false): void
    {
    }

    public function remove(Organization $entity, bool $flush = false): void
    {
    }
}
