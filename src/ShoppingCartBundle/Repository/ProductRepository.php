<?php

namespace ShoppingCartBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use ShoppingCartBundle\Entity\Category;

class ProductRepository extends EntityRepository
{
    public function findAllByQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder("product")
            ->andWhere("product.quantity > 0")
            ->andWhere("product.isListed = 1")
            ->orderBy("product.id", "desc");
    }

    public function findLastProducts(int $productsCount): array
    {
        return $this->createQueryBuilder("product")
            ->andwhere("product.quantity > 0")
            ->andWhere("product.isListed = 1")
            ->setMaxResults($productsCount)
            ->orderBy("product.id", "desc")
            ->getQuery()
            ->execute();
    }

    public function findAllbyCategoryQueryBuilder(Category $category): QueryBuilder
    {
        return $this->createQueryBuilder("product")
            ->andWhere("product.quantity > 0")
            ->andWhere("product.isListed = 1")
            ->andWhere("product.category = :category")
            ->orderBy("product.id", "desc")
            ->setParameter("category", $category);
    }
}