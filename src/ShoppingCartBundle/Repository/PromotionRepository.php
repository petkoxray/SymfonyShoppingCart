<?php

namespace ShoppingCartBundle\Repository;


use Doctrine\ORM\EntityRepository;

class PromotionRepository extends EntityRepository
{
    public function findAllByQueryBuilder()
    {
        return $this->createQueryBuilder("promotion")
            ->orderBy("promotion.endDate", "asc");
    }
}