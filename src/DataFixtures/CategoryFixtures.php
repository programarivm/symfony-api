<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $category = new Category();
        $category->setName('eCommerce');
        $category->setSlug('ecommerce');
        $category->setDescription('This is the description.');
        $manager->persist($category);

        $manager->flush();
    }
}
