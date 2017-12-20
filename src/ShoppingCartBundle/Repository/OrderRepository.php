<?php

namespace ShoppingCartBundle\Repository;


use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use ShoppingCartBundle\Entity\User;

class OrderRepository extends EntityRepository
{
    public function findOrdersByUserQueryBuilder(User $user): QueryBuilder
    {
        return $this->createQueryBuilder('users_orders')
            ->andWhere("users_orders.user = :user")
            ->setParameter("user", $user)
            ->orderBy("users_orders.createdAt", "desc");
    }

    public function findAllByQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder('users_orders')
            ->orderBy("users_orders.createdAt", "desc");
    }

    public function findByQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder('users_orders');
    }
}