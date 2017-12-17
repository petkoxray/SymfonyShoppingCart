<?php

namespace ShoppingCartBundle\Service;


use ShoppingCartBundle\Entity\Product;
use ShoppingCartBundle\Entity\Review;
use ShoppingCartBundle\Entity\User;

interface ReviewServiceInterface
{
    public function addReview(Review $review): void;

    public function deleteReview(Review $review): void;

    public function getReviewByUserAndProduct(User $user, Product $product): ?Review;
}