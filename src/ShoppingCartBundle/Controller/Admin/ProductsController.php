<?php

namespace ShoppingCartBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use ShoppingCartBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


/**
 * Class ProductsController
 * @package ShoppingCartBundle\Controller\Admin
 *
 * @Security("is_granted('ROLE_EDITOR')")
 * @Route("/admin")
 */
class ProductsController extends Controller
{
    /**
     * @Route("/products/", name="admin_products_all")
     * @Method("GET")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function allProductsAction(): Response
    {
        $products = $this->getDoctrine()->getRepository(Product::class)->findAll();

        return $this->render("ShoppingCartBundle:admin/products:all.html.twig", [
            "products" => $products
        ]);
    }

    /**
     * @Route("/products/add")
     *
     * @param Request $request
     * @return Response
     */
    public function addProductsAction(Request $request): Response
    {

    }

    /**
     * @Route("/products/edit/{slug}")
     *
     * @param Request $request
     * @param Product $product
     * @return Response
     */
    public function editProductsAction(Request $request, Product $product): Response
    {

    }

    /**
     * @Route("/products/delete/{slug}")
     *
     * @param Product $product
     * @return Response
     */
    public function deleteProductsAction(Product $product): Response
    {

    }
}
