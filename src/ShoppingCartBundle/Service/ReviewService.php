<?php

namespace ShoppingCartBundle\Service;


use Doctrine\ORM\EntityManagerInterface;
use ShoppingCartBundle\Entity\Product;
use ShoppingCartBundle\Entity\Review;
use ShoppingCartBundle\Entity\User;
use ShoppingCartBundle\Repository\ReviewRepository;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class ReviewService implements ReviewServiceInterface
{
    /**
     * @var ReviewRepository
     */
    private $reviewRepository;

    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(
        ReviewRepository $reviewRepository,
        FlashBagInterface $flashBag,
        EntityManagerInterface $entityManager
    ) {
        $this->reviewRepository = $reviewRepository;
        $this->flashBag = $flashBag;
        $this->entityManager = $entityManager;
    }

    public function addReview(Review $review)
    {
        $this->entityManager->persist($review);
        $this->entityManager->flush();
        $this->flashBag->set("success", "Review added!");
    }

    public function getReviewByUserAndProduct(User $user, Product $product): ?Review
    {
        return $this->reviewRepository->findOneByProductAndUser($user, $product);
    }

    public function deleteReview(Review $review)
    {
        $this->entityManager->remove($review);
        $this->entityManager->flush();
        $this->flashBag->set("success", "Review deleted!");
    }
}