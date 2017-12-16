<?php

namespace ShoppingCartBundle\Service;


use ShoppingCartBundle\Entity\Product;
use ShoppingCartBundle\Entity\Review;
use ShoppingCartBundle\Entity\User;

interface ReviewServiceInterface
{
    public function addReview(Review $review);

    public function deleteReview(Review $review);

    public function getReviewByUserAndProduct(User $user, Product $product): ?Review;
}