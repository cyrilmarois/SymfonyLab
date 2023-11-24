<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use App\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\PasswordStrength;
use App\State\UserPasswordHasher;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ApiResource(mercure: true, operations: [
    new Get(),
    new Post(processor: UserPasswordHasher::class)
])]
#[UniqueEntity(fields: ['email'], message: 'An account is already registered for this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(unique: true, length: 255)]
    #[Assert\NotBlank]
    #[Assert\Email]
    public ?string $email = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $password = null;

    #[Assert\NotBlank]
    #[Assert\PasswordStrength(['minScore' => PasswordStrength::STRENGTH_VERY_STRONG])]
    private ?string $plainPassword = null;


    #[ORM\Column(name: 'is_active', type: Types::BOOLEAN)]
    private ?bool $isActive = null;

    #[ORM\Column(name: 'first_name', length: 255, nullable: true)]
    public ?string $firstName = null;

    #[ORM\Column(name: 'last_name', length: 255, nullable: true)]
    public ?string $lastName = null;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[ORM\Column(name: 'created_at')]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(name: 'updated_at', nullable: true)]
    private ?\DateTime $updatedAt = null;

    #[ORM\Column(name: 'deleted_at', nullable: true)]
    private ?\DateTimeImmutable $deletedAt = null;

    public function __construct()
    {
        // $this->roles = ['ROLE_USER'];
        $this->isActive = true; // to be validate by email
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

    public function getIsActive()
    {
        return $this->isActive;
    }

    public function getCreated()
    {
        return $this->createdAt;
    }

    public function getUpdated()
    {
        return $this->createdAt;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function eraseCredentials()
    {
        $this->plainPassword = '';
    }

    public function getUserIdentifier(): string
    {
        return (string)$this->email;
    }


    public function setPassword(string $password)
    {
        $this->password = \hash('sha256', $password);

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }
}
