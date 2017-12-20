<?php

namespace ShoppingCartBundle\Controller;

use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use ShoppingCartBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class UserProductsController
 * @package ShoppingCartBundle\Controller
 *
 * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
 */
class UserProductsController extends Controller
{
    private $paginator;

    public function __construct(PaginatorInterface $paginator)
    {
        $this->paginator = $paginator;
    }


    /**
     * @Route("/user/products", name="user_products")
     *
     * @param Request $request
     * @return Response
     */
    public function userProductsAction(Request $request): Response
    {
        $userProducts = $this->paginator->paginate(
            $this->getDoctrine()->getRepository(Product::class)
                ->findProductsByUserByQueryBuilder($this->getUser()),
            $request->query->getInt('page', 1),
            5
        );

        return $this->render("@ShoppingCart/users/products/all.html.twig", [
            'products' => $userProducts
        ]);
    }

    /**
     * @Route("/user/products/sell/{slug}", name="user_sell_product")
     *
     * @param Product $product
     * @return Response
     */
    public function sellUserProductAction(Product $product): Response
    {
        if ($product->isListed()) {
            $this->addFlash('danger', 'Product is already for sale!');
        } else {
            $product->setIsListed(true);
            $this->addFlash('success', 'Product is putted for sale :)');
        }

        $this->getDoctrine()->getManager()->flush();
        return $this-> redirectToRoute('user_products');
    }
}
