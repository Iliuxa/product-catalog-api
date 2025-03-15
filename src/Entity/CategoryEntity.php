<?php

namespace App\Entity;

use App\Attribute\DtoObject;
use App\Dto\CategoryDto;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

#[DtoObject(CategoryDto::class)]
#[Entity]
#[Table(name: "CATEGORY")]
class CategoryEntity extends AbstractEntity
{
    #[Id]
    #[GeneratedValue]
    #[Column(type: Types::INTEGER)]
    private int $id;

    #[Column(type: Types::STRING, length: 255, unique: true)]
    private string $name;

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): CategoryEntity
    {
        $this->name = $name;
        return $this;
    }
}