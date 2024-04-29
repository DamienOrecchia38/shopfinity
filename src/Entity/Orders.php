<?php

namespace App\Entity;

use App\Repository\OrdersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

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
    private ?Users $users = null;

    /**
     * @var Collection<int, products>
     */
    #[ORM\ManyToMany(targetEntity: Products::class, inversedBy: 'orders')]
    private Collection $relation_orders_products;

    #[ORM\Column(length: 255)]
    private ?string $order_number = null;

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
}
