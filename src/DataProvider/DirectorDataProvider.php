<?php

declare(strict_types=1);

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\Director;

/**
 * Simple data provider as example other than Doctrine ORM.
 * Third party API can be used here for example.
 */
class DirectorDataProvider implements ContextAwareCollectionDataProviderInterface, ItemDataProviderInterface, RestrictedDataProviderInterface
{
    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return Director::class === $resourceClass;
    }

    public function getCollection(string $resourceClass, string $operationName = null, array $context = [])
    {
        return $this->getDirectors();
    }

    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = [])
    {
        $director = array_filter($this->getDirectors(), function (Director $director) use ($id) {
            return $director->getId() === $id;
        });

        if (count($director) !== 1) {
            return null;
        }

        return current($director);
    }

    /**
     * @return Director[]
     */
    private function getDirectors(): array
    {
        return [
            (new Director())->setId('abc123')->setName('Steven Spielberg'),
            (new Director())->setId('def456')->setName('Jon Favreau'),
        ];
    }
}
