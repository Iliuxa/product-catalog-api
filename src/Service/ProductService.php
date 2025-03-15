<?php

namespace App\Service;

use App\Dto\ProductDto;
use App\Entity\CategoryEntity;
use App\Entity\ProductEntity;
use App\Exception\Notice;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;

class ProductService extends BasicService
{
    public function __construct(
        protected EntityManager $entityManager,
        private readonly DaDataService   $daDataService,
    )
    {
        parent::__construct($this->entityManager);
    }

    public function getByFilter(ProductDto $dto): array
    {
        return $this->entityManager->getRepository(ProductEntity::class)->getByDto($dto);
    }

    public function get(int $id): ProductEntity
    {
        return $this->entityManager->find(ProductEntity::class, $id)
            ?? throw new Notice('Товар не найден!', 404);
    }

    public function delete(int $id): void
    {
        try {
            $product = $this->entityManager->find(ProductEntity::class, $id);
            $this->entityManager->remove($product);
            $this->entityManager->flush();
        } catch (ORMException $exception) {
            throw new Notice('Ошибка при удалении товара!');
        }
    }

    /**
     * @param ProductDto $dto
     * @return void
     * @throws Notice
     */
    public function save(ProductDto $dto): void
    {
        try {
            if (!$this->daDataService->isValidInn($dto->inn)) {
                throw new Notice('Не корректный ИНН!');
            }

            $product = $dto->id === null ? new ProductEntity() : $this->entityManager->find(ProductEntity::class, $dto->id);
            $product
                ->setName($dto->name)
                ->setInn($dto->inn)
                ->setEan13($dto->ean13)
                ->setDescription($dto->description);

            $product->getCategories()->clear();
            foreach ($dto->categories as $categoryDto) {
                $category = $this->entityManager->find(CategoryEntity::class, $categoryDto->id)
                    ?? throw new Notice('Категория не найдена!', 404);
                $product->addCategory($category);
            }

            $this->entityManager->persist($product);
            $this->entityManager->flush();
        } catch (ORMException $exception) {

            throw new Notice('Ошибка при сохранении товара!');
        }
    }
}