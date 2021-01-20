<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ProductFixtures extends Fixture
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
            $product = (new Product())
                ->setName($this->faker->sentence($nbWords = 5, $variableNbWords = true))
                ->setPrice($this->faker->randomNumber(2))
                ->setCurrency($this->currencies[array_rand($this->currencies)])
                ->setIsFeatured($this->faker->boolean($chanceOfGettingTrue = 33));
            $manager->persist($product);
        }

        $manager->flush();
    }
}
