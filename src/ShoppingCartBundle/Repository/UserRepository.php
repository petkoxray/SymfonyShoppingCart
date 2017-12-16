<?php

namespace ShoppingCartBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class UserRepository extends EntityRepository
{
    public function findAllByQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder("user")
            ->orderBy('user.id', 'asc');
    }
}