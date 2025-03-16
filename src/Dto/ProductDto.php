<?php

namespace App\Dto;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "ProductDto",
    required: ["name", "inn", "ean13"],
    properties: [
        new OA\Property(property: "id", type: "integer", example: 1, nullable: true),
        new OA\Property(property: "name", type: "string", example: "Телефон"),
        new OA\Property(property: "inn", type: "string", example: "1234567890"),
        new OA\Property(property: "ean13", type: "string", example: "9876543210000"),
        new OA\Property(property: "description", type: "string", example: "Описание продукта", nullable: true),
        new OA\Property(property: "categories", type: "array", items: new OA\Items(ref: "#/components/schemas/CategoryDto"), nullable: true)
    ],
    type: "object"
)]
class ProductDto implements DtoInterface
{
    public ?int $id = null;

    public ?string $name = null;

    public ?string $inn = null;

    public ?string $ean13 = null;

    public ?string $description = null;

    /**
     * @var CategoryDto[]|null
     */
    public ?array $categories = null;
}