<?php

namespace ShoppingCartBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ShoppingCartBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(): Response
    {
        $products = $this->getDoctrine()->getRepository(Product::class)
            ->findLastProducts(6);

        return $this->render('@ShoppingCart/index.html.twig', [
            'products' => $products
        ]);
    }
}
