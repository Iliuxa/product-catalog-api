<?php

namespace App\Service;

use App\Dto\ProductDto;
use App\Entity\ProductEntity;

class ProductService extends BasicService
{
    public function get(ProductDto $dto): array
    {
        return $this->entityManager->getRepository(ProductEntity::class)->getByDto($dto);
    }
}