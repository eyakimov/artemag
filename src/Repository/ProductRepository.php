<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ProductRepository extends ServiceEntityRepository {

    public function __construct(RegistryInterface $registry) {
        parent::__construct($registry, Product::class);
    }

    public function findGreaterByAmount($value) {
        return $this->createQueryBuilder('p')
                        ->where('p.amount > :value ')
                        ->setParameter('value', $value)
                        ->getQuery()
                        ->getResult()
        ;
    }

    public function findByCategory($category) {

        return $this->createQueryBuilder('p')
                        ->leftJoin('p.categories', 'c')
                        ->where('c.id = :category AND p.amount > :value ')
                        ->setParameter('category', $category)
                        ->setParameter('value', 0)
                        ->getQuery()
                        ->getResult()
        ;
    }

}
