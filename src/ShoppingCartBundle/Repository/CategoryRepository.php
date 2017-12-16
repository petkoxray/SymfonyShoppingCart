<?php

namespace ShoppingCartBundle\Repository;


use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class CategoryRepository extends EntityRepository
{
    public function findAllByQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder("category")
            ->orderBy('category.id', 'asc');
    }
}