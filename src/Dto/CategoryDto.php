<?php

namespace App\Dto;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "CategoryDto",
    description: "DTO категории",
    properties: [
        new OA\Property(property: "id", type: "integer", example: 2, nullable: true),
        new OA\Property(property: "name", type: "string", example: "Еда")
    ],
    type: "object"
)]
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