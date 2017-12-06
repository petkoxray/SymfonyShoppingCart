<?php

namespace ShoppingCartBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ShoppingCartBundle\Entity\Product;
use ShoppingCartBundle\Entity\Review;
use ShoppingCartBundle\Form\ReviewAddForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ProductController
 * @package ShoppingCartBundle\Controller
 */
class ProductController extends Controller
{
    /**
     * @Route("/products/{slug}", name="show_product")
     * @Method("GET")
     *
     * @param Product $product
     * @return Response
     */
    public function showProductAction(Product $product): Response
    {
        return $this->render("@ShoppingCart/products/show_product.html.twig", [
            "product" => $product,
        ]);
    }


    /**
     * @Route("/products/{slug}/review")
     *
     * @param Response $response
     * @param Product $product
     * @return Response
     */
//    public function addReviewAction(Response $response, Product $product): Response
//    {
//
//    }
}