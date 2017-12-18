<?php

namespace ShoppingCartBundle\Service;


use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use ShoppingCartBundle\Entity\Category;
use ShoppingCartBundle\Entity\Promotion;
use ShoppingCartBundle\Repository\PromotionRepository;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class PromotionService implements PromotionServiceInterface
{
    private $promotionRepository;
    private $flashBag;
    private $entityManager;
    private $manager;

    public function __construct(
        PromotionRepository $promotionRepository,
        FlashBagInterface $flashBag,
        EntityManagerInterface $entityManager,
        ManagerRegistry $manager
    ) {
        $this->promotionRepository = $promotionRepository;
        $this->flashBag = $flashBag;
        $this->entityManager = $entityManager;
        $this->manager = $manager;
    }

    public function applyPromotionToCategory(Promotion $promotion, Category $category): void
    {
        foreach ($category->getProducts() as $product) {
            if ($product->getPromotions()->contains($promotion)) {
                continue;
            }
            $product->addPromotion($promotion);
        }

        $this->entityManager->flush();
        $this->flashBag->add('success',
            "Promotion {$promotion->getName()} has been applied to Category {$category->getName()}");
    }

    public function applyPromotionToAllProducts(Promotion $promotion): void
    {
        $products = $this->manager->getRepository("ShoppingCartBundle:Product")
            ->findAll();

        foreach ($products as $product) {
            if ($product->getPromotions()->contains($promotion)) {
                continue;
            }

            $product->addPromotion($promotion);
        }

        $this->flashBag->add('success',
            "Promotion {$promotion->getName()} has been applied to all products");
        $this->entityManager->flush();
    }

    public function removeExpiredPromotionsFromProducts(): void
    {

    }
}