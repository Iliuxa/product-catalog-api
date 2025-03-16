<?php

namespace  App\Tests\Controller;

use App\Dto\CategoryDto;
use App\Dto\ProductDto;
use App\Entity\CategoryEntity;
use App\Entity\ProductEntity;
use Doctrine\ORM\EntityManager;

class ProductControllerTest extends BasicTestCase
{
    private EntityManager $entityManager;

    public function testGetProductByFilter(): void
    {
        $categories = $this->entityManager->getRepository(CategoryEntity::class)->findAll();

        $filters = [];
        $filter = new ProductDto();
        $filter->name = 'Товар2';
        $filters[] = $filter;

        $filter = new ProductDto();
        $filter->categories = [new CategoryDto()->setId($categories[0]->getId())];
        $filters[] = $filter;

        $filter = new ProductDto();
        $filter->name = 'Товар1';
        $filter->categories = [new CategoryDto()->setId($categories[2]->getId())];
        $filters[] = $filter;

        $filter = new ProductDto();
        $filter->inn = '1234567890';
        $filter->ean13 = '1234567890';
        $filters[] = $filter;

        foreach ($filters as $filter) {
            $response = $this->sendRequest('/products', 'POST', $filter);
            $this->assertEquals(200, $response['status']);
            $this->assertIsArray($response['body']);

            $products = $this->entityManager->getRepository(ProductEntity::class)->getByDto($filter);
            $this->assertCount(count($products), $response['body']);

            if (!empty($response['body'])) {
                foreach (new ProductDto() as $fieldName => $fieldValue) {
                    $this->assertArrayHasKey($fieldName, $response['body'][0]);
                }
            }
        }
    }

    public function testGet(): void
    {
        $products = $this->entityManager->getRepository(ProductEntity::class)->findBy([], null, 10);

        foreach ($products as $product) {
            $response = $this->sendRequest('/product/' . $product->getId(), 'GET');

            $this->assertEquals(200, $response['status']);
            foreach (new ProductDto() as $fieldName => $fieldValue) {
                $this->assertArrayHasKey($fieldName, $response['body']);
            }
            $this->assertEquals($product->getId(), $response['body']['id']);
            $this->assertEquals($product->getName(), $response['body']['name']);
            $this->assertEquals($product->getInn(), $response['body']['inn']);
            $this->assertEquals($product->getEan13(), $response['body']['ean13']);
            $this->assertEquals($product->getDescription(), $response['body']['description']);

            $categories = array_column($response['body']['categories'], 'name', 'id');
            foreach ($product->getCategories() as $category) {
                $this->assertArrayHasKey($category->getId(), $categories);
            }
        }

        $response = $this->sendRequest('/product/' . 1000000, 'GET');
        $this->assertEquals(404, $response['status']);
        $this->assertEquals('Товар не найден!', ($response['body']['message']));
    }

    public function testDelete(): void
    {
        $products = $this->entityManager->getRepository(ProductEntity::class)->findAll();
        $this->assertNotEmpty($products);
        $productId = $products[0]->getId();
        $response = $this->sendRequest('/product/' . $productId, 'DELETE');

        $this->assertEquals(200, $response['status']);

        $this->entityManager->clear();
        $product = $this->entityManager->find(ProductEntity::class, $productId);
        $this->assertNull($product);
    }

    public function testSave(): void
    {
        $categories = $this->entityManager->getRepository(CategoryEntity::class)->findAll();
        $dataValid = [
            'name' => 'Товар11',
            'inn' => '7707083893',
            'ean13' => '9876543210000',
            'description' => 'Телефон',
            'categories' => [['id' => $categories[0]->getId()], ['id' => $categories[1]->getId()]]
        ];
        $dataInvalidInn = [
            'name' => 'Товар12',
            'inn' => '7707083',
            'ean13' => '9876543210000',
            'description' => 'Ноут',
            'categories' => [['id' => $categories[0]->getId()]]
        ];
        $dataDuplicate = [
            'name' => 'Товар12',
            'inn' => '7707083893',
            'ean13' => '9876543210000',
            'description' => 'Ноут',
            'categories' => [['id' => $categories[0]->getId()]]
        ];
        $dataCategoryNotFound = [
            'name' => 'Товар13',
            'inn' => '7707083893',
            'ean13' => '9876543210034',
            'description' => 'Телефон',
            'categories' => [['id' => 10000]]
        ];

        $response = $this->sendRequest('/product', 'POST', $dataValid);
        $this->assertEquals(200, $response['status']);
        $product = $this->entityManager->getRepository(ProductEntity::class)->findOneBy([
            'inn' => $dataValid['inn'],
            'ean13' => $dataValid['ean13']
        ]);
        $this->assertNotNull($product);

        $response = $this->sendRequest('/product', 'POST', $dataInvalidInn);
        $this->assertEquals(500, $response['status']);
        $this->assertEquals('Не корректный ИНН!', ($response['body']['message']));

        $response = $this->sendRequest('/product', 'POST', $dataDuplicate);
        $this->assertEquals(500, $response['status']);

        $response = $this->sendRequest('/product', 'POST', $dataCategoryNotFound);
        $this->assertEquals(404, $response['status']);
        $this->assertEquals('Категория не найдена!', ($response['body']['message']));
    }

    protected function setUp(): void
    {
        $this->entityManager = require dirname(__DIR__) . '/bootstrap.php';
    }

    protected function tearDown(): void
    {
        $this->entityManager->close();
    }
}