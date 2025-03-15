<?php

namespace App\Dto;

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