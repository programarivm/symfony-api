<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CategoryFixtures extends Fixture
{
    const N = 3;

    private $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < self::N; $i++) {
            $name = rtrim($this->faker->sentence($nbWords = 3, $variableNbWords = true), '.');
            $category = (new Category())
                            ->setName($name)
                            ->setSlug($this->slug($name))
                            ->setDescription($this->faker->sentence);
            $manager->persist($category);
            $this->addReference("category-$i", $category);
        }

        $manager->flush();
    }

    private function slug($string)
    {
        return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string)));
    }
}
