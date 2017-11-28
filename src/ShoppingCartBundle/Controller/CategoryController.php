<?php

namespace ShoppingCartBundle\Controller;

use ShoppingCartBundle\Entity\Category;
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
}