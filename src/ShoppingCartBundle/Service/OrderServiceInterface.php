<?php

namespace ShoppingCartBundle\Service;

use ShoppingCartBundle\Entity\Order;
use ShoppingCartBundle\Entity\User;

interface OrderServiceInterface
{
    public function createOrder(User $user,array $products, float $totalPrice );

    public function completeOrder(Order $order): void;
}