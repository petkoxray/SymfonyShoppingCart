<?php

namespace ShoppingCartBundle\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use ShoppingCartBundle\Entity\Product;
use ShoppingCartBundle\Entity\Review;
use ShoppingCartBundle\Entity\User;
use Doctrine\ORM\Mapping;

class ReviewRepository extends EntityRepository
{
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, new Mapping\ClassMetadata(Review::class));
    }

    public function findOneByProductAndUser(User $user, Product $product)
    {
        return $this->findOneBy([
            "author" => $user,
            "product" => $product
        ]);
    }
}