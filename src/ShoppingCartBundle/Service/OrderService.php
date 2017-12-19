<?php

namespace ShoppingCartBundle\Service;

use ShoppingCartBundle\Entity\Order;
use ShoppingCartBundle\Entity\User;

class OrderService implements OrderServiceInterface
{
    public function createOrder(User $user, array $products, float $totalPrice)
    {
        $order = new Order();
        $order->setUser($user);
        $order->setProducts($products);
        $order->setTotal($totalPrice);

        return $order;
    }
}