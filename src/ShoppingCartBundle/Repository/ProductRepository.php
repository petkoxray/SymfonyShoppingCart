<?php

namespace ShoppingCartBundle\Repository;

use Doctrine\ORM\EntityRepository;
use ShoppingCartBundle\Entity\Category;

class ProductRepository extends EntityRepository
{
    public function findAllByQueryBuilder()
    {
        return $this->createQueryBuilder("product")
            ->where("product.quantity > 0")
            ->orderBy("product.id", "desc");
    }

    public function findLastProducts(int $productsCount)
    {
        return $this->createQueryBuilder("product")
            ->where("product.quantity > 0")
            ->setMaxResults($productsCount)
            ->orderBy("product.id", "desc")
            ->getQuery()
            ->execute();
    }

    public function findAllbyCategoryQueryBuilder(Category $category)
    {
        return $this->createQueryBuilder("product")
            ->where("product.quantity > 0")
            ->andWhere("product.category = :category")
            ->orderBy("product.id", "desc")
            ->setParameter("category", $category);
    }
}