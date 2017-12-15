<?php

namespace ShoppingCartBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ShoppingCartBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CartController extends Controller
{
    /**
     *@Route("/cart/add")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $product = $this->getDoctrine()->getRepository(Product::class)
            ->find(1);
        $this->getUser()->getCart()->add($product);
        $em = $this->getDoctrine()->getManager();
        $em->persist($this->getUser());
        $em->flush();
        exit();
        return $this->redirectToRoute('homepage');
    }
}
