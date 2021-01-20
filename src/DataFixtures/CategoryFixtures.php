<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    private $sample = [
        [
            'eCommerce',
            'ecommerce',
            'Lorem ipsum dolor sit amet',
        ],
        [
            'Entertainment',
            'entertainment',
            'Consectetur adipiscing elit',
        ],
        [
            'Wiki',
            'wiki',
            'sed do eiusmod tempor incididunt',
        ],
    ];

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < count($this->sample); $i++) {
            $category = (new Category())
                            ->setName($this->sample[$i][0])
                            ->setSlug($this->sample[$i][1])
                            ->setDescription($this->sample[$i][2]);
            $manager->persist($category);
            $this->addReference("category-$i", $category);
        }

        $manager->flush();
    }
}
