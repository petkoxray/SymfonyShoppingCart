<?php

namespace ShoppingCartBundle\Service;


use ShoppingCartBundle\Entity\Category;
use ShoppingCartBundle\Entity\Promotion;

interface PromotionServiceInterface
{
    public function applyPromotionToCategory(Promotion $promotion, Category $category): void;

    public function applyPromotionToAllProducts(Promotion $promotion): void;

    public function deleteExpiredPromotions(): void;

    public function removeExpiredPromotionsFromProducts(): void;
}