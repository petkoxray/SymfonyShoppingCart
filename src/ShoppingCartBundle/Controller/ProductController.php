<?php

namespace ShoppingCartBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use ShoppingCartBundle\Entity\Product;
use ShoppingCartBundle\Entity\Review;
use ShoppingCartBundle\Form\ReviewAddForm;
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
     * @Route("/products", name="products_all")
     * @Method("GET")
     *
     * @param Request $request
     * @return Response
     */
    public function allProductsAction(Request $request): Response
    {
        $paginator  = $this->get('knp_paginator');
        $products = $paginator->paginate(
            $this->getDoctrine()->getRepository(Product::class)
                ->findByQueryBuilder(),
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
        return $this->render("@ShoppingCart/products/show_product.html.twig", [
            "product" => $product,
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
        $form = $this->createForm(ReviewAddForm::class);
        $form->handleRequest($request);

        if ($form->isValid()) {
            /** @var Review $review */
            $review = $form->getData();
            $review->setAuthor($this->getUser());
            $review->setProduct($product);

            $em = $this->getDoctrine()->getManager();
            $em->persist($review);
            $em->flush();

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
     * @param Product $product
     * @return Response
     */
    public function deleteReviewAction(Request $request, Review $review): Response
    {
        $product = $review->getProduct();

        if ($this->getUser()->getId() === $review->getAuthor()->getId()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($review);
            $em->flush();

            $this->addFlash("success", "Review was deleted successfully!");

            return $this->redirectToRoute('product_show', ["slug" => $product->getSlug()]);
        } else {
            throw $this->createAccessDeniedException('You cannot access this page!');
        }
    }
}