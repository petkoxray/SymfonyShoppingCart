<?php

namespace ShoppingCartBundle\Controller;

use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use ShoppingCartBundle\Entity\Product;
use ShoppingCartBundle\Entity\Review;
use ShoppingCartBundle\Form\ReviewAddForm;
use ShoppingCartBundle\Service\ReviewServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ProductController
 * @package ShoppingCartBundle\Controller
 */
class ProductController extends Controller
{
    /**
     * @var ReviewServiceInterface $reviewService
     */
    private $reviewService;

    public function __construct(ReviewServiceInterface $reviewService)
    {
        $this->reviewService = $reviewService;
    }

    /**
     * @Route("/products", name="products_all")
     * @Method("GET")
     *
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function allProductsAction(Request $request, PaginatorInterface $paginator): Response
    {
        $products = $paginator->paginate(
            $this->getDoctrine()->getRepository(Product::class)
                ->findAllByQueryBuilder(),
            $request->query->getInt('page', 1),
            9
        );

        return $this->render('@ShoppingCart/products/all.html.twig', [
            "products" => $products
        ]);
    }

    /**
     * @Route("/products/{slug}", name="product_show")
     * @Method("GET")
     *
     * @param Product $product
     * @return Response
     */
    public function showProductAction(Product $product): Response
    {
        $review = $this->reviewService->getReviewByUserAndProduct(
            $this->getUser(), $product
        );

        return $this->render("@ShoppingCart/products/show_product.html.twig", [
            "product" => $product,
            "review" => $review,
            "review_add" => $this->createForm(ReviewAddForm::class)->createView()
        ]);
    }


    /**
     * @Route("/products/{slug}/review/add", name="product_add_review")
     * @Method("POST")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @param Request $request
     * @param Product $product
     * @return Response
     */
    public function addReviewAction(Request $request, Product $product): Response
    {
        $review = $this->reviewService->getReviewByUserAndProduct(
            $this->getUser(), $product
        );

        if ($review) {
            $this->addFlash("danger", "You already has reviewed this product!");
            return $this->redirectToRoute('product_show', ['slug' => $product->getSlug()]);
        }

        $form = $this->createForm(ReviewAddForm::class);
        $form->handleRequest($request);

        if ($form->isValid()) {
            /** @var Review $review */
            $review = $form->getData();
            $review->setAuthor($this->getUser());
            $review->setProduct($product);
            $this->reviewService->addReview($review);

            return $this->redirectToRoute('product_show', ['slug' => $product->getSlug()]);
        }

        return $this->render("@ShoppingCart/products/show_product.html.twig", [
            "product" => $product,
            "review_add" => $form->createView()
        ]);
    }

    /**
     * @Route("/reviews/{id}/delete", name="product_delete_review")
     * @Method("POST")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @param Request $request
     * @param Review $review
     * @return Response
     */
    public function deleteReviewAction(Request $request, Review $review): Response
    {
        $product = $review->getProduct();

        if ($this->getUser()->getId() === $review->getAuthor()->getId()) {
            $this->reviewService->deleteReview($review);

            return $this->redirectToRoute('product_show', ["slug" => $product->getSlug()]);
        } else {
            throw $this->createAccessDeniedException('You cannot access this page!');
        }
    }
}