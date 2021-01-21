<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function symbols($iso)
    {
        switch ($iso) {
            case 'EUR':
                return 'USD';
            case 'USD';
                return 'EUR';
        }
    }

    public function featured($iso, $rate)
    {
        $result = array_merge(
            $this->convert($iso, $rate),
            $this->dontConvert($iso)
        );

        usort($result, function ($a, $b) {
            return $a['id'] - $b['id'];
        });

        return $result;
    }

    private function convert($iso, $rate)
    {
        return $this->createQueryBuilder('p')
            ->join('p.category', 'c')
            ->select("p.id, p.name, p.price * $rate as price, p.currency, c.name as category_name")
            ->where('p.isFeatured = :featured')
            ->andWhere('p.currency != :iso')
            ->setParameter('featured', true)
            ->setParameter('iso', $iso)
            ->getQuery()
            ->getResult()
        ;
    }

    private function dontConvert($iso)
    {
        return $this->createQueryBuilder('p')
            ->join('p.category', 'c')
            ->select("p.id, p.name, p.price, p.currency, c.name as category_name")
            ->where('p.isFeatured = :featured')
            ->andWhere('p.currency = :iso')
            ->setParameter('featured', true)
            ->setParameter('iso', $iso)
            ->getQuery()
            ->getResult()
        ;
    }
}
