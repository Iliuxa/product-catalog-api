<?php

namespace App\DataFixtures;

use App\Entity\CategoryEntity;
use App\Entity\ProductEntity;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProductFixture implements FixtureInterface, DependentFixtureInterface
{
    private const array productsData = [
        [
            'name' => 'Товар1',
            'inn' => '1234567890',
            'ean13' => '1234567890',
            'description' => 'aaaaaaa',
        ], [
            'name' => 'Товар2',
            'inn' => '987654331',
            'ean13' => '54782436589',
            'description' => 'aaaaaaa'
        ]
    ];

    public function load(ObjectManager $manager): void
    {
        $categories = $manager->getRepository(CategoryEntity::class)->findAll();
        foreach (self::productsData as $productData) {
            $product = new ProductEntity();
            foreach ($productData as $fieldName => $value) {
                $product->{'set' . ucfirst($fieldName)}($value);
            }

            for ($i = 0; $i < rand(1, 4); $i++) {
                $product->addCategory($categories[$i]);
            }
            $manager->persist($product);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [CategoryFixture::class];
    }
}