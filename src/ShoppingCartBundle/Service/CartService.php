<?php

namespace ShoppingCartBundle\Service;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use ShoppingCartBundle\Entity\Product;
use ShoppingCartBundle\Entity\User;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class CartService implements CartServiceInterface
{
    private $flashBag;
    private $entityManager;
    private $manager;
    private $productService;
    private $orderService;

    public function __construct(
        FlashBagInterface $flashBag,
        EntityManagerInterface $entityManager,
        ManagerRegistry $manager,
        ProductServiceInterface $productService,
        OrderServiceInterface $orderService
    ) {
        $this->flashBag = $flashBag;
        $this->entityManager = $entityManager;
        $this->manager = $manager;
        $this->productService = $productService;
        $this->orderService = $orderService;
    }

    public function addToCart(Product $product, User $user): bool
    {
        if ($user->getCart()->contains($product)) {
            $this->flashBag->add('danger', 'You already have this product in you cart!');
            return false;
        }

        if ($product->getSeller() === $user) {
            $this->flashBag->add('danger', 'You can\'t add your own product to the cart!');
            return false;
        }

        $user->getCart()->add($product);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        $this->flashBag->add('success', "{$product->getName()} added to your cart!");

        return true;
    }

    public function getCartTotal(User $user): float
    {
        return array_reduce($user->getCart()->toArray(), function ($sum, Product $product) {
                $sum += $product->getPrice();
                return $sum;
            }) ?? 0;
    }


    public function removeFromCart(Product $product, User $user): bool
    {
        if (!$user->getCart()->contains($product)) {
            $this->flashBag->add('danger', 'You don\'t have this product in you cart!');
            return false;
        }

        $user->getCart()->removeElement($product);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        $this->flashBag->add('success', "{$product->getName()} removed from your cart!");

        return true;
    }

    public function checkoutCart(User $user): bool
    {
        $cartProducts = $user->getCart();
        $cartTotal = $this->getCartTotal($user);

        if (count($cartProducts) === 0) {
            $this->flashBag
                ->add('danger',
                    "Cart is empty!");
            return false;
        }

        if (!$this->productService->isProductsAvailable($cartProducts)) {
            $this->flashBag
                ->add('danger',
                    "Some products are out of stock! Please remove them to proceed!");
            return false;
        }

        if ($user->getMoney() < $cartTotal) {
            $this->flashBag
                ->add('danger',
                    "You don't have enough money to complete your order!");
            return false;
        }

        $orderedProducts = [];
        foreach ($cartProducts as $product) {
            $product->setQuantity($product->getQuantity() - 1);
            $user->setMoney($user->getMoney() - $product->getPrice());
            $user->getCart()->removeElement($product);
            $orderedProducts[$product->getSlug()] = $product->getName();

            /**
             * @var User $seller
             */
            $seller = $product->getSeller();
            $seller->setMoney($seller->getMoney() + $product->getPrice());
        }

        $order = $this->orderService->createOrder($user, $orderedProducts, $cartTotal);
        $this->entityManager->persist($order);
        $this->entityManager->flush();

        $this->flashBag
            ->add('success',
                "Order has been received!");
        return true;
    }
}