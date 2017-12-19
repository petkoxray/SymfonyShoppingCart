<?php

namespace ShoppingCartBundle\Service;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use ShoppingCartBundle\Entity\Product;
use ShoppingCartBundle\Entity\User;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class CartService implements CartServiceInterface
{
    private $promotionRepository;
    private $flashBag;
    private $entityManager;
    private $manager;
    private $productService;

    public function __construct(
        FlashBagInterface $flashBag,
        EntityManagerInterface $entityManager,
        ManagerRegistry $manager,
        ProductServiceInterface $productService
    ) {
        $this->flashBag = $flashBag;
        $this->entityManager = $entityManager;
        $this->manager = $manager;
        $this->productService = $productService;
    }

    public function addToCart(Product $product, User $user): bool
    {
        if ($user->getCart()->contains($product)) {
            $this->flashBag->add('danger', 'You already have this product in you cart!');
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
        if (!$this->productService->isProductsInStock($cartProducts)) {
            $this->flashBag
                ->add('danger',
                    "Some products are out of stock! Please remove them to proceed!");
            return false;
        }

        $userMoney = $user->getMoney();
        if ($userMoney < $this->getCartTotal($user)) {
            $this->flashBag
                ->add('danger',
                    "You don't have enough money to complete your order!");
            return false;
        }

        foreach ($cartProducts as $product)
        {
            $product->setQuantity($product->getQuantity() - 1);
            $user->getCart()->removeElement($product);
            /**
             * @var User $seller
             */
            $seller = $product->getSeller();
            $seller->setMoney($seller->getMoney() + $product->getPrice());
        }

        $this->entityManager->flush();
        $this->flashBag
            ->add('success',
                "Order successful!");
        return true;
    }
}