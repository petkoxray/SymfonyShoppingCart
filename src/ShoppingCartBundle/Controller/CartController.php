<?php

namespace ShoppingCartBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use ShoppingCartBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class CartController
 * @package ShoppingCartBundle\Controller
 *
 * @Route("/cart")
 * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
 */
class CartController extends Controller
{
    /**
     * @Route("/add")
     *
     * @return Response
     */
    public function indexAction(): Response
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
