<?php

namespace App\Entity;

use App\Repository\CategoriesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoriesRepository::class)]
class Categories
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $title = null;

    /**
     * @var Collection<int, products>
     */
    #[ORM\OneToMany(targetEntity: products::class, mappedBy: 'categories')]
    private Collection $relation_categories_products;

    public function __construct()
    {
        $this->relation_categories_products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return Collection<int, products>
     */
    public function getRelationCategoriesProducts(): Collection
    {
        return $this->relation_categories_products;
    }

    public function addRelationCategoriesProduct(products $relationCategoriesProduct): static
    {
        if (!$this->relation_categories_products->contains($relationCategoriesProduct)) {
            $this->relation_categories_products->add($relationCategoriesProduct);
            $relationCategoriesProduct->setCategories($this);
        }

        return $this;
    }

    public function removeRelationCategoriesProduct(products $relationCategoriesProduct): static
    {
        if ($this->relation_categories_products->removeElement($relationCategoriesProduct)) {
            // set the owning side to null (unless already changed)
            if ($relationCategoriesProduct->getCategories() === $this) {
                $relationCategoriesProduct->setCategories(null);
            }
        }

        return $this;
    }
}
