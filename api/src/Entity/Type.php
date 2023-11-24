<?php

namespace App\Entity;

use ApiPlatform\metadata\ApiResource;
use App\Repository\TypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource(paginationEnabled: false)]
#[ORM\Entity(repositoryClass: TypeRepository::class)]
class Type
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    public ?string $slug = null;

    #[ORM\Column(name: 'created_at')]
    private ?\DateTimeImmutable $createdAt;

    #[ORM\Column(name: 'updated_at', nullable: true)]
    private ?\DateTime $updatedAt = null;

    #[ORM\Column(name: 'deleted_at', nullable: true)]
    private ?\DateTimeImmutable $deletedAt = null;

    /**
     * Many Types have Many Pokemons (INVERSE SIDE)
     * @var Collection<int, Pokemon>
     */
    public Collection $pokemons;

    public function __construct()
    {
        $this->pokemons = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getCreated()
    {
        return $this->createdAt;
    }

    public function getUpdated()
    {
        return $this->createdAt;
    }
}
