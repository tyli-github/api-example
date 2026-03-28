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

        $this->initialize();

        if (isset($uriVariables['id'])) {
            $id = (int)$uriVariables['id'];

            return array_find(self::$directors, fn($director) => $director->getId() === $id);
        }

        return self::$directors;
    }

    public static function getDirectors(): array
    {
        self::initialize();

        return self::$directors;
    }

    public static function setDirectors(array $directors): void
    {
        self::$directors = $directors;
    }

    public static function reset(): void
    {
        self::$directors = [];
        self::$initialized = false;
    }

    private static function initialize(): void
    {
        if (!self::$initialized) {
            self::$directors = [
                new Director()->setId(1)->setName('Steven Spielberg'),
                new Director()->setId(2)->setName('Jon Favreau'),
            ];
            self::$initialized = true;
        }
    }
}
