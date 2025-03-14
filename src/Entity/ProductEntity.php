<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\InverseJoinColumn;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\Table;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\UniqueConstraint;

#[Entity]
#[Table(name: "PRODUCT", uniqueConstraints: [
    new UniqueConstraint(name: "unique_product", columns: ["inn", "ean13"])
])]
class ProductEntity
{
    #[Id]
    #[GeneratedValue]
    #[Column(type: Types::INTEGER)]
    private int $id;

    #[Column(type: Types::STRING, length: 255)]
    private string $name;

    #[Column(type: Types::STRING, length: 12)]
    private string $inn;

    #[Column(type: Types::STRING, length: 13)]
    private string $ean13;

    #[Column(type: Types::TEXT, nullable: true)]
    private ?string $description;

    #[JoinTable(name: "product_category")]
    #[JoinColumn(name: 'product_id', referencedColumnName: 'id')]
    #[InverseJoinColumn(name: 'category_id', referencedColumnName: 'id')]
    #[ManyToMany(targetEntity: CategoryEntity::class)]
    private Collection $categories;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): ProductEntity
    {
        $this->name = $name;
        return $this;
    }

    public function getInn(): string
    {
        return $this->inn;
    }

    public function setInn(string $inn): ProductEntity
    {
        $this->inn = $inn;
        return $this;
    }

    public function getEan13(): string
    {
        return $this->ean13;
    }

    public function setEan13(string $ean13): ProductEntity
    {
        $this->ean13 = $ean13;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): ProductEntity
    {
        $this->description = $description;
        return $this;
    }

    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(CategoryEntity $category): void
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
        }
    }
}