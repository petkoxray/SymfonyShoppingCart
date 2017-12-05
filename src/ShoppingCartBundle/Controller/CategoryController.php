<?php

namespace ShoppingCartBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ShoppingCartBundle\Entity\Category;
use ShoppingCartBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends Controller
{
    public function sidebarCategoriesAction(): Response
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();

        return $this->render('@ShoppingCart/_sidebar.html.twig', [
            "categories" => $categories
        ]);
    }

    /**
     * @Route("/categories/{slug}", name="show_products_by_category")
     * @Method("GET")
     */
    public function showCategoryAction(Category $category): Response
    {
        $products = $this->getDoctrine()->getRepository(Product::class)
            ->findBy(['category' => $category]);

        return $this->render('ShoppingCartBundle:categories:category.html.twig', [
            'products' => $products,
            'category' => $category
        ]);
    }
}