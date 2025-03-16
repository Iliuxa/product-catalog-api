<?php

namespace App\Service;

use App\Dto\ProductDto;
use App\Entity\CategoryEntity;
use App\Entity\ProductEntity;
use App\Exception\Notice;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;
use Monolog\Logger;

class ProductService extends BasicService
{
    public function __construct(
        protected EntityManager        $entityManager,
        protected Logger               $logger,
        private readonly DaDataService $daDataService,
    )
    {
        parent::__construct($this->entityManager, $this->logger);
    }

    /**
     * @param ProductDto $dto
     * @return array
     */
    public function getByFilter(ProductDto $dto): array
    {
        return $this->entityManager->getRepository(ProductEntity::class)->getByDto($dto);
    }

    /**
     * @param int $id
     * @return ProductEntity
     * @throws Notice
     */
    public function get(int $id): ProductEntity
    {
        try {
            return $this->entityManager->find(ProductEntity::class, $id)
                ?? throw new Notice('Товар не найден!', 404);
        } catch (ORMException $exception) {
            $this->logger->error($exception->getMessage());
            throw new Notice('Ошибка при получении товара!');
        }
    }

    /**
     * @param int $id
     * @return void
     * @throws Notice
     */
    public function delete(int $id): void
    {
        try {
            $product = $this->entityManager->find(ProductEntity::class, $id)
                ?? throw new Notice('Товар не найден!', 404);
            $this->entityManager->remove($product);
            $this->entityManager->flush();
        } catch (ORMException $exception) {
            $this->logger->error($exception->getMessage());
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
                $this->logger->info($dto->inn . ' не найден');
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
            $this->logger->error($exception->getMessage());
            throw new Notice('Ошибка при сохранении товара!');
        }
    }
}