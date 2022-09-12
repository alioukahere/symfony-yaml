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
        $organizations = $this->readYamlFile();

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
            fn (Organization $data)
                => strtolower($data->getName()) === strtolower($name)
        );
        if (empty($search)) {
            return null;
        }

        return array_values($search)[0];
    }

    public function add(Organization $entity, bool $flush = false): void
    {
    }

    public function remove(Organization $entity): void
    {
        $organizations = $this->readYamlFile();
        $index = array_search(
            $entity->getName(),
            array_column($organizations, 'name')
        );
        if (false === $index) {
            throw new UnexpectedValueException(
                "Failed getting organization with name {$entity->getName()}"
            );
        }

        array_splice($organizations, $index, 1);
        $data['organizations'] = $organizations;
        $this->writeYamlFile($data);
    }

    private function readYamlFile(): array
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

        return $organizations;
    }

    private function writeYamlFile(array $organizations): void
    {
        $data = Yaml::dump($organizations, 5, 2);
        file_put_contents($this->yamlFilePath, $data);
    }
}
