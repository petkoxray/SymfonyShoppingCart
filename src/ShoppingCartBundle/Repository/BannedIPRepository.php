<?php

namespace ShoppingCartBundle\Repository;


use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping;
use ShoppingCartBundle\Entity\BannedIP;

class BannedIPRepository extends EntityRepository
{
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, new Mapping\ClassMetadata(BannedIP::class));
    }
}