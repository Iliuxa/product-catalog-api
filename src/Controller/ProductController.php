<?php

namespace App\Controller;


use App\Dto\CategoryDto;
use App\Dto\ProductDto;
use App\Exception\ApiException;
use App\Service\ProductService;

class ProductController extends BasicController
{
    public function __construct(
        private readonly ProductService $service
    )
    {
    }

    /**
     * @param array $postData
     * @return ProductDto[]
     * @throws ApiException
     */
    public function get(array $postData): array
    {
        $dto = $this->arrayToDto($postData, ProductDto::class, ['categories' => CategoryDto::class]);
        return $this->service->toItemDto($this->service->get($dto));
    }
}