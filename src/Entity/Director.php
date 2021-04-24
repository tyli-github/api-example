<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ApiResource(
 *     collectionOperations={
 *          "get"={
 *              "method"="GET",
 *              "path"="/director"
 *          }
 *     },
 *     itemOperations={
 *          "get" = {
 *              "method"="GET",
 *              "path"="/director/{id}"
 *          }
 *     }
 * )
 */
class Director
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
