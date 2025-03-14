<?php

namespace App\Dto;

use JsonSerializable;

class ProductDto
{
    public ?int $id = null;

    public ?string $name = null;

    public ?string $inn = null;

    public ?string $ean13 = null;

    public ?int $categoryId = null;

}