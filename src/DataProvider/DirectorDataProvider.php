<?php

declare(strict_types=1);

namespace App\DataProvider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Entity\Director;

/**
 * State provider as example for in-memory data.
 * Third party APIs can be used here as well.
 */
class DirectorDataProvider implements ProviderInterface
{
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        if (!is_a($operation->getClass(), Director::class, true)) {
            return null;
        }

        // Item operation (single director)
        if (isset($uriVariables['id'])) {
            return $this->getItem((int) $uriVariables['id']);
        }

        // Collection operation
        return $this->getCollection();
    }

    private function getCollection(): array
    {
        return $this->getDirectors();
    }

    private function getItem(int $id): ?Director
    {
        $directors = array_filter(
            $this->getDirectors(),
            fn(Director $director) => $director->getId() === $id
        );

        return count($directors) === 1 ? current($directors) : null;
    }

    /**
     * @return Director[]
     */
    private function getDirectors(): array
    {
        return [
            new Director()->setId(1)->setName('Steven Spielberg'),
            new Director()->setId(2)->setName('Jon Favreau'),
        ];
    }
}
