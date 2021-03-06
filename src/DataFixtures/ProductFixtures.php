<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ProductFixtures extends Fixture implements DependentFixtureInterface
{
    const N = 50;

    private $faker;

    private $currencies = [
        'EUR',
        'USD',
    ];

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < self::N; $i++) {
            $name = rtrim($this->faker->sentence($nbWords = 4, $variableNbWords = true), '.');
            $product = (new Product())
                ->setName($name)
                ->setPrice($this->faker->randomNumber(2))
                ->setCurrency($this->currencies[array_rand($this->currencies)])
                ->setIsFeatured($this->faker->boolean($chanceOfGettingTrue = 33))
                ->setCategory($this->getReference('category-'.rand(0, CategoryFixtures::N-1)));
            $manager->persist($product);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CategoryFixtures::class,
        ];
    }
}
