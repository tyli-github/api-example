<?php

declare(strict_types=1);

namespace App\DataProvider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Entity\Director;

/**
 * State Provider for in-memory Director data (static storage).
 * Must be set on all READ operations, or API Platform falls back to Doctrine and then 404.
 * @see DirectorDataProcessor
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

        // Lazy-load static data on first access
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
            // Hardcoded sample data
            self::$directors = [
                new Director()->setId(1)->setName('Steven Spielberg'),
                new Director()->setId(2)->setName('Jon Favreau'),
            ];
            self::$initialized = true;
        }
    }
}
