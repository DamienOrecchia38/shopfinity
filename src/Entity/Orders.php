<?php

namespace App\Entity;

use App\Repository\OrdersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: OrdersRepository::class)]
class Orders
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $created_at = null;

    #[ORM\ManyToOne(inversedBy: 'relation_users_orders')]
    #[Assert\NotNull(message: "L'utilisateur associé à la commande ne peut pas être nul.")]
    private ?Users $users = null;

    /**
     * @var Collection<int, Products>
     */
    #[ORM\ManyToMany(targetEntity: Products::class, inversedBy: 'orders')]
    #[Assert\Count(
        min: 1,
        minMessage: "La commande doit contenir au moins un produit."
    )]
    private Collection $relation_orders_products;

    #[ORM\Column(length: 255)]
    private ?string $order_number = null;

    #[ORM\Column]
    private ?int $quantity = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $total = null;

    public function __construct()
    {
        $this->relation_orders_products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getUsers(): ?Users
    {
        return $this->users;
    }

    public function setUsers(?Users $users): static
    {
        $this->users = $users;

        return $this;
    }

    /**
     * @return Collection<int, Products>
     */
    public function getRelationOrdersProducts(): Collection
    {
        return $this->relation_orders_products;
    }

    public function addRelationOrdersProduct(Products $relationOrdersProduct): static
    {
        if (!$this->relation_orders_products->contains($relationOrdersProduct)) {
            $this->relation_orders_products->add($relationOrdersProduct);
        }

        return $this;
    }

    public function removeRelationOrdersProduct(Products $relationOrdersProduct): static
    {
        $this->relation_orders_products->removeElement($relationOrdersProduct);

        return $this;
    }

    public function getOrderNumber(): ?string
    {
        return $this->order_number;
    }

    public function setOrderNumber(string $order_number): static
    {
        $this->order_number = $order_number;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getTotal(): ?string
    {
        return $this->total;
    }

    public function setTotal(string $total): static
    {
        $this->total = $total;

        return $this;
    }
}
