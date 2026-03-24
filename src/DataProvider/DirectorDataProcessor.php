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
 * State processor for in-memory director mutations.
 */
class DirectorDataProcessor implements ProcessorInterface
{
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

            return null;
        }

        return $data;
    }

    private function handleCreate(Director $director, array &$directors): Director
    {
        $nextId = !empty($directors) ? max(array_map(fn($d) => $d->getId(), $directors)) + 1 : 1;
        $director->setId($nextId);
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
                unset($directors[$key]);
                DirectorDataProvider::setDirectors(array_values($directors));

                return;
            }
        }

        throw new RuntimeException(sprintf('Director with id %d not found', $id));
    }
}
