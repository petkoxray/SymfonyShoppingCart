<?php

namespace ShoppingCartBundle\Service;


use ShoppingCartBundle\Entity\Product;
use ShoppingCartBundle\Entity\User;

interface CartServiceInterface
{
    public function addToCart(Product $product, User $user): bool;

    public function getCartTotal(User $user): float;

    public function removeFromCart(Product $product, User $user): bool;

    public function checkoutCart(User $user);
}