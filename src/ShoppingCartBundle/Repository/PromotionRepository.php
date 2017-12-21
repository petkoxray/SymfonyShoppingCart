<?php

namespace ShoppingCartBundle\Repository;


use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use ShoppingCartBundle\Entity\Promotion;
use Doctrine\ORM\Mapping;

class PromotionRepository extends EntityRepository
{
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, new Mapping\ClassMetadata(Promotion::class));
    }

    public function findAllByQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder("promotion")
            ->orderBy("promotion.endDate", "asc");
    }

    public function findAllAvailableByQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder("promotion")
            ->andWhere("promotion.startDate < :date")
            ->andWhere('promotion.endDate > :date')
            ->setParameter('date', new \DateTime('now'))
            ->orderBy("promotion.endDate", "asc");
    }

    public function findAllExpiredPromotions()
    {
        return $this->createQueryBuilder('promotion')
            ->andWhere('promotion.endDate < :date')
            ->setParameter('date', new \DateTime('now'))
            ->getQuery()
            ->execute();
    }
}