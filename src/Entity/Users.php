<?php

namespace App\Entity;

use App\Repository\UsersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UsersRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'Il existe déjà un compte avec cet email')]
class Users implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    #[Assert\Email(
        message: 'L\'adresse email "{{ value }}" n\'est pas une adresse email valide.'
    )]
    #[Assert\NotBlank(
        message: 'L\'adresse email ne peut pas être vide.'
    )]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Assert\Length(
        min: 8,
        minMessage: 'Le mot de passe doit comporter au moins {{ limit }} caractères.'
    )]
    private ?string $password = null;

    #[ORM\Column(length: 60)]
    #[Assert\NotBlank(
        message: 'Le nom ne peut pas être vide.'
    )]
    #[Assert\Length(
        min: 2,
        max: 60,
        minMessage: 'Le nom doit comporter au minimum {{ limit }} caractères.',
        maxMessage: 'Le nom doit comporter au maximum {{ limit }} caractères.'
    )]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $created_at = null;

    /**
     * @var Collection<int, Orders>
     */
    #[ORM\OneToMany(targetEntity: Orders::class, mappedBy: 'users')]
    private Collection $relation_users_orders;

    public function __construct()
    {
        $this->created_at = new \DateTime();
        $this->relation_users_orders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // pour garantir que chaque user à au moins le ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // $this->plainPassword = null;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * @return Collection<int, orders>
     */
    public function getRelationUsersOrders(): Collection
    {
        return $this->relation_users_orders;
    }

    public function addRelationUsersOrder(orders $relationUsersOrder): static
    {
        if (!$this->relation_users_orders->contains($relationUsersOrder)) {
            $this->relation_users_orders->add($relationUsersOrder);
            $relationUsersOrder->setUsers($this);
        }

        return $this;
    }

    public function removeRelationUsersOrder(orders $relationUsersOrder): static
    {
        if ($this->relation_users_orders->removeElement($relationUsersOrder)) {
            if ($relationUsersOrder->getUsers() === $this) {
                $relationUsersOrder->setUsers(null);
            }
        }

        return $this;
    }
}
