<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\PokemonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Validator\AssertCanDeletePokemon;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    shortName: 'Pokemon',
    mercure: true,
    operations: [
        new Get(),
        new GetCollection(),
        new Post(),
        new Put(
            denormalizationContext: [
                'groups' => ['pokemon:write'],
            ]
        ),
        new Patch(
            denormalizationContext: [
                'groups' => ['pokemon:write'],
            ]
        ),
        new Delete(
            validate: true,
            validationContext: ['groups' => ['deleteValidation']],
            exceptionToStatus: [ValidationException::class => Response::HTTP_FORBIDDEN]
        )
    ],
    paginationItemsPerPage: 50,
    formats: ['jsonld', 'json'],
)]
#[ORM\Entity(repositoryClass: PokemonRepository::class)]
#[AssertCanDeletePokemon(groups: ['deleteValidation'])]
class Pokemon
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[ApiFilter(SearchFilter::class, strategy: 'ipartial')]
    #[Groups(['pokemon:write'])]
    public ?string $name = null;

    #[ORM\Column(type: Types::SMALLINT)]
    public ?int $total = null;

    #[ORM\Column(type: Types::SMALLINT)]
    public ?int $health = null;

    #[ORM\Column(type: Types::SMALLINT)]
    public ?int $attack = null;

    #[ORM\Column(type: Types::SMALLINT)]
    public ?int $defense = null;

    #[ORM\Column(name: 'attack_special', type: Types::SMALLINT)]
    public ?int $attackSpecial = null;

    #[ORM\Column(name: 'defense_special', type: Types::SMALLINT)]
    public ?int $defenseSpecial = null;

    #[ORM\Column(type: Types::SMALLINT)]
    public ?int $speed = null;

    #[ORM\Column(type: Types::SMALLINT)]
    #[ApiFilter(RangeFilter::class)]
    #[Groups(['pokemon:write'])]
    public ?int $generation = null;

    #[ORM\Column(type: Types::BOOLEAN)]
    #[ApiFilter(BooleanFilter::class)]
    #[Groups(['pokemon:write'])]
    public ?bool $legendary = false;

    #[ORM\Column(name: 'created_at')]
    private ?\DateTimeImmutable $createdAt;

    #[ORM\Column(name: 'updated_at', nullable: true)]
    private ?\DateTime $updatedAt = null;

    #[ORM\Column(name: 'deleted_at', nullable: true)]
    private ?\DateTimeImmutable $deletedAt = null;

    /**
     * Many Pokemons have Many Types (OWNING SIDE)
     * @var Collection<int, Type>
     *
     * @var Collection
     */
    #[ORM\ManyToMany(targetEntity: Type::class, inversedBy: 'pokemon')]
    #[ORM\JoinTable(name: 'pokemon_type')]
    #[ORM\JoinColumn(name: 'pokemon_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'type_id', referencedColumnName: 'id')]
    #[ApiFilter(SearchFilter::class, strategy: 'iexact')]
    #[Groups(['pokemon:write'])]
    public Collection $types;

    public function __construct()
    {
        $this->types = new ArrayCollection();
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
