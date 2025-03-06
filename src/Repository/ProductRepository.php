<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    // Recherche des produits par nom, description, et catégorie
    public function searchProducts(string $searchTerm = '', $category = null, $minPrice = null, $maxPrice = null)
    {
        $queryBuilder = $this->createQueryBuilder('p');

        if ($searchTerm) {
            $queryBuilder
                ->andWhere('p.name LIKE :searchTerm OR p.description LIKE :searchTerm')
                ->setParameter('searchTerm', '%' . $searchTerm . '%');
        }

        if ($category) {
            $queryBuilder
                ->andWhere('p.category = :category')
                ->setParameter('category', $category);
        }

        if ($minPrice) {
            $queryBuilder
                ->andWhere('p.price >= :minPrice')
                ->setParameter('minPrice', $minPrice);
        }

        if ($maxPrice) {
            $queryBuilder
                ->andWhere('p.price <= :maxPrice')
                ->setParameter('maxPrice', $maxPrice);
        }

        return $queryBuilder->getQuery()->getResult();
    }    
}
