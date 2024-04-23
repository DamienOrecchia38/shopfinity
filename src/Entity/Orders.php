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
    #[ORM\ManyToMany(targetEntity: products::class, inversedBy: 'orders')]
    private Collection $relation_orders_products;

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
     * @return Collection<int, products>
     */
    public function getRelationOrdersProducts(): Collection
    {
        return $this->relation_orders_products;
    }

    public function addRelationOrdersProduct(products $relationOrdersProduct): static
    {
        if (!$this->relation_orders_products->contains($relationOrdersProduct)) {
            $this->relation_orders_products->add($relationOrdersProduct);
        }

        return $this;
    }

    public function removeRelationOrdersProduct(products $relationOrdersProduct): static
    {
        $this->relation_orders_products->removeElement($relationOrdersProduct);

        return $this;
    }
}
