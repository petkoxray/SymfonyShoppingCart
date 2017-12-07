<?php

namespace ShoppingCartBundle\Repository;

use Doctrine\ORM\EntityRepository;

class ProductRepository extends EntityRepository
{
    public function findByQueryBuilder()
    {
        return $this->createQueryBuilder("product")
            ->where("product.quantity > 0")
            ->orderBy("product.id", "desc");
    }
}