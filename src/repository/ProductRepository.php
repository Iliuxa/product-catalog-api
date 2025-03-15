<?php

namespace App\repository;

use App\Dto\CategoryDto;
use App\Dto\ProductDto;
use App\Entity\ProductEntity;
use Doctrine\ORM\EntityRepository;

class ProductRepository extends EntityRepository
{
    /**
     * Получение товаров по фильтру
     * @param ProductDto $dto
     * @return ProductEntity[]
     */
    public function getByDto(ProductDto $dto): array
    {
        $queryBuilder = $this->createQueryBuilder('product');

        if ($dto->name !== null) {
            $queryBuilder->andWhere($queryBuilder->expr()->like('product.name', ':name'))
                ->setParameter('name', "%$dto->name%");
        }

        if ($dto->inn !== null) {
            $queryBuilder->andWhere($queryBuilder->expr()->eq('product.inn', ':inn'))
                ->setParameter('inn', $dto->inn);
        }

        if ($dto->ean13 !== null) {
            $queryBuilder->andWhere($queryBuilder->expr()->eq('product.ean13', ':ean13'))
                ->setParameter('ean13', $dto->ean13);
        }

        if (!empty($dto->categories)) {
            $queryBuilder->innerJoin('product.categories', 'category')
                ->andWhere($queryBuilder->expr()->in('category.id', ':categoryIds'))
                ->setParameter('categoryIds', array_map(fn(CategoryDto $categoryDto) => $categoryDto->id, $dto->categories) );
        }

        return $queryBuilder->getQuery()->getResult();
    }
}