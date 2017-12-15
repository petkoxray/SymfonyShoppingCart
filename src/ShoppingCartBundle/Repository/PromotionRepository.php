<?php

namespace ShoppingCartBundle\Repository;


use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class PromotionRepository extends EntityRepository
{
    public function findAllByQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder("promotion")
            ->orderBy("promotion.endDate", "asc");
    }
}