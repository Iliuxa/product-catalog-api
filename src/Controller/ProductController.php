<?php

namespace App\Controller;


use App\Dto\CategoryDto;
use App\Dto\DtoInterface;
use App\Dto\ProductDto;
use App\Service\ProductService;

class ProductController extends BasicController
{
    public function __construct(
        private readonly ProductService $service
    )
    {
    }

    public function getByFilter(array $postData): array
    {
        $dto = $this->arrayToDto($postData, ProductDto::class, ['categories' => CategoryDto::class]);
        return $this->service->toItemDto($this->service->getByFilter($dto));
    }

    public function get(array $postData, int $id): DtoInterface
    {
        return $this->service->get($id)->toDto();
    }

    public function delete(array $postData, int $id): void
    {
        $this->service->delete($id);
    }

    public function save(array $postData): void
    {
        $dto = $this->arrayToDto($postData, ProductDto::class, ['categories' => CategoryDto::class]);
        $this->service->save($dto);
    }
}