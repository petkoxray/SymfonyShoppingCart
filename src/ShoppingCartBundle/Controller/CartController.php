<?php

namespace ShoppingCartBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use ShoppingCartBundle\Entity\Product;
use ShoppingCartBundle\Entity\User;
use ShoppingCartBundle\Service\CartServiceInterface;
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
    private $cartService;

    /**
     * @param CartServiceInterface $cartService
     */
    public function __construct(CartServiceInterface $cartService)
    {
        $this->cartService = $cartService;
    }


    /**
     * @Route("/show", name="cart_show")
     *
     * @return Response
     */
    public function indexAction(): Response
    {
        return $this->render("@ShoppingCart/cart/show_cart.html.twig", [
            "cart" => $this->getUser()->getCart(),
            "cartTotal" => $this->cartService->getCartTotal($this->getUser())
        ]);
    }

    /**
     * @Route("/add/{slug}", name="cart_add")
     *
     * @param $product
     * @return Response
     */
    public function addToCartAction(Product $product): Response
    {
        $this->cartService->addToCart($product, $this->getUser());
        return $this->redirectToRoute('cart_show');
    }

    /**
     * @Route("/remove/{slug}", name="cart_remove")
     *
     * @param $product
     * @return Response
     */
    public function removeFromCartAction(Product $product): Response
    {
        $this->cartService->removeFromCart($product, $this->getUser());
        return $this->redirectToRoute('cart_show');
    }

    /**
     * @Route("/checkout", name="cart_checkout")
     *
     * @return Response
     */
    public function checkoutCartAction(): Response
    {
        if (!$this->cartService->checkoutCart($this->getUser())) {
            return $this->redirectToRoute('cart_show');
        }

        return $this->redirectToRoute("homepage");
    }
}
