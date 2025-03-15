<?php

namespace App\DataFixtures;

use App\Entity\CategoryEntity;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CategoryFixture implements FixtureInterface
{
    private const array categoryNames = [
        'еда',
        'вода',
        'дерево',
        'железо',
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::categoryNames as $categoryName) {
            $category = new CategoryEntity()->setName($categoryName);
            $manager->persist($category);
        }

        $manager->flush();
    }
}