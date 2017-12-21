<?php

namespace ShoppingCartBundle\Service;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use ShoppingCartBundle\Entity\Order;
use ShoppingCartBundle\Entity\Product;
use ShoppingCartBundle\Entity\User;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class OrderService implements OrderServiceInterface
{
    private $flashBag;
    private $manager;
    private $entityManager;

    public function __construct(
        FlashBagInterface $flashBag,
        EntityManagerInterface $entityManager,
        ManagerRegistry $manager
    ) {
        $this->flashBag = $flashBag;
        $this->manager = $manager;
        $this->entityManager = $entityManager;
    }

    public function createOrder(User $user, array $products, float $totalPrice)
    {
        $order = new Order();
        $order->setUser($user);
        $order->setProducts($products);
        $order->setTotal($totalPrice);

        return $order;
    }

    public function completeOrder(Order $order): bool
    {
        if ($order->isCompleted()) {
            $this->flashBag->set("danger", "Order is already completed!");

            return false;
        }

        $order->setCompleted(true);

        foreach ($order->getProducts() as $slug => $name) {
            $product = $this->manager->getRepository(Product::class)
                ->findOneBy(['slug' => $slug]);
            $userProduct = new Product();
            $userProduct->setIsResold(true);
            $userProduct->setName($product->getName());
            $userProduct->setQuantity(1);
            $userProduct->setCategory($product->getCategory());
            $userProduct->setPrice($product->getPrice());
            $userProduct->setImageName($product->getImageName());
            $userProduct->setDescription($product->getDescription());
            $userProduct->setIsListed(false);
            $userProduct->setSeller($order->getUser());

            $this->entityManager->persist($userProduct);
        }

        $this->entityManager->flush();
        $this->flashBag->set(
            "success", "Order is completed and products are added to the user!");

        return true;
    }
}