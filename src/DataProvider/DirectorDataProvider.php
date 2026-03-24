<?php

declare(strict_types=1);

namespace App\DataProvider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Entity\Director;

/**
 * State provider for in-memory director data with static storage.
 * Provides read operations (GetCollection, Get).
 */
class DirectorDataProvider implements ProviderInterface
{
    private static array $directors = [];
    private static bool $initialized = false;

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        if (!is_a($operation->getClass(), Director::class, true)) {
            return null;
        }

        $this->initializeStorage();

        // Item operation (single director)
        if (isset($uriVariables['id'])) {
            return $this->getItem((int)$uriVariables['id']);
        }

        // Collection operation
        return $this->getCollection();
    }

    public static function getDirectors(): array
    {
        if (!self::$initialized) {
            self::$directors = [
                new Director()->setId(1)->setName('Steven Spielberg'),
                new Director()->setId(2)->setName('Jon Favreau'),
            ];
            self::$initialized = true;
        }

        return self::$directors;
    }

    public static function setDirectors(array $directors): void
    {
        self::$directors = $directors;
    }

    private function initializeStorage(): void
    {
        if (!self::$initialized) {
            self::$directors = [
                new Director()->setId(1)->setName('Steven Spielberg'),
                new Director()->setId(2)->setName('Jon Favreau'),
            ];
            self::$initialized = true;
        }
    }

    private function getCollection(): array
    {
        return self::$directors;
    }

    private function getItem(int $id): ?Director
    {
        $directors = array_filter(
            self::$directors,
            fn(Director $director) => $director->getId() === $id
        );

        return count($directors) === 1 ? current($directors) : null;
    }
}
