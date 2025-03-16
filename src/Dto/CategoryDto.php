<?php

namespace App\Dto;

class CategoryDto implements DtoInterface
{
    public ?int $id = null;

    public ?string $name = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): CategoryDto
    {
        $this->id = $id;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): CategoryDto
    {
        $this->name = $name;
        return $this;
    }
}