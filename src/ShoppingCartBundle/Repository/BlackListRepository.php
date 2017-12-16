<?php

namespace ShoppingCartBundle\Repository;


use Doctrine\ORM\EntityManagerInterface;
use ShoppingCartBundle\Entity\BlackList;

class BlackListRepository
{
    private $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->repository = $entityManager->getRepository(BlackList::class);
    }

    public function findAll(): array
    {
        return $this->repository->findAll();
    }
}