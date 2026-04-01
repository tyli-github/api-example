<?php

declare(strict_types=1);

namespace App\DataProvider;

use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Director;
use RuntimeException;

/**
 * Processor for in-memory Director write operations.
 * Dispatches Post/Put/Patch/Delete to handlers that mutate DirectorDataProvider::$directors.
 * @see DirectorDataProvider
 */
class DirectorDataProcessor implements ProcessorInterface
{
    /**
     * Main processor entry point for all Director write operations.
     *
     * @return Director|null The processed entity (null for Delete)
     * @throws RuntimeException If entity isn't found (404 equivalent)
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        if (!$data instanceof Director) {
            return $data;
        }

        $directors = DirectorDataProvider::getDirectors();

        if ($operation instanceof Post) {
            return $this->handleCreate($data, $directors);
        }

        if ($operation instanceof Put) {
            return $this->handleReplace($data, $uriVariables, $directors);
        }

        if ($operation instanceof Patch) {
            return $this->handleUpdate($data, $uriVariables, $directors);
        }

        if ($operation instanceof Delete) {
            $this->handleDelete($uriVariables, $directors);
            // API Platform converts NULL return to "204 No Content"
            return null;
        }

        return $data;
    }

    private function handleCreate(Director $director, array &$directors): Director
    {
        // Calculate next ID (max existing + 1, or 1 if a collection is empty)
        $nextId = !empty($directors) ? max(array_map(fn($d) => $d->getId(), $directors)) + 1 : 1;
        $director->setId($nextId);

        // Append to a collection and persist back to the provider
        $directors[] = $director;
        DirectorDataProvider::setDirectors($directors);

        return $director;
    }

    private function handleReplace(Director $director, array $uriVariables, array &$directors): Director
    {
        $id = (int)$uriVariables['id'];
        foreach ($directors as $key => $existing) {
            if ($existing->getId() === $id) {
                $director->setId($id);
                $directors[$key] = $director;
                DirectorDataProvider::setDirectors($directors);

                return $director;
            }
        }

        throw new RuntimeException(sprintf('Director with id %d not found', $id));
    }

    private function handleUpdate(Director $director, array $uriVariables, array &$directors): Director
    {
        $id = (int)$uriVariables['id'];
        foreach ($directors as $key => $existing) {
            if ($existing->getId() === $id) {
                // merge only non-null fields from the request
                if ($director->getName() !== null) {
                    $existing->setName($director->getName());
                }

                $directors[$key] = $existing;

                DirectorDataProvider::setDirectors($directors);

                return $existing;
            }
        }

        throw new RuntimeException(sprintf('Director with id %d not found', $id));
    }

    private function handleDelete(array $uriVariables, array &$directors): void
    {
        $id = (int)$uriVariables['id'];

        foreach ($directors as $key => $director) {
            if ($director->getId() === $id) {
                // Remove from an array and re-index to maintain sequential keys
                unset($directors[$key]);

                DirectorDataProvider::setDirectors(array_values($directors));

                return;
            }
        }

        throw new RuntimeException(sprintf('Director with id %d not found', $id));
    }
}
