<?php

namespace ShoppingCartBundle\Controller;

use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use ShoppingCartBundle\Entity\Product;
use ShoppingCartBundle\Entity\User;
use ShoppingCartBundle\Form\UserProductEditForm;
use ShoppingCartBundle\Service\ReviewServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Class UserProductsController
 * @package ShoppingCartBundle\Controller
 *
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
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param Request $request
     * @return Response
     */
    public function userProductsAction(Request $request): Response
    {
        $userProducts = $this->paginator->paginate(
            $this->getDoctrine()->getRepository(Product::class)
                ->findProductsByUserByQueryBuilder($this->getUser()),
            $request->query->getInt('page', 1),
            6
        );

        return $this->render("@ShoppingCart/users/products/all.html.twig", [
            'products' => $userProducts
        ]);
    }

    /**
     * @Route("/user/products/sell/{slug}", name="user_sell_product")
     *
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param Product $product
     * @return Response
     */
    public function sellOwnedProductsAction(Product $product): Response
    {
        if ($product->getSeller() !== $this->getUser()) {
            throw new AccessDeniedException('You are not the owner of this product!');
        }

        if ($product->isListed()) {
            $this->addFlash('danger', 'Product is already for sale!');
        } else {
            $product->setIsListed(true);
            $this->addFlash('success', 'Product is putted for sale :)');
        }

        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('user_products');
    }


    /**
     * @Route("/user/products/unsell/{slug}", name="user_unsell_product")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param Product $product
     * @return Response
     */
    public function removeFromSaleOwnedProductsAction(Product $product): Response
    {
        if ($product->getSeller() !== $this->getUser()) {
            throw new AccessDeniedException('You are not the owner of this product!');
        }

        if (!$product->isListed()) {
            $this->addFlash('danger', 'Product is not for sale!');
        } else {
            $product->setIsListed(false);
            $this->addFlash('success', 'Product is removed from sale.)');
        }

        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('user_products');
    }

    /**
     * @Route("/user/products/edit/{slug}", name="user_product_edit")
     *
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @return Response
     */
    public function editProduct(Request $request, Product $product): Response
    {
        if ($product->getSeller() !== $this->getUser()) {
            throw new AccessDeniedException('You are not the owner of this product!');
        }

        $form = $this->createForm(UserProductEditForm::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Product edited succesfully');
            return $this->redirectToRoute('user_products');
        }

        return $this->render('@ShoppingCart/users/products/edit.html.twig', [
            'edit_form' => $form->createView()
        ]);
    }

    /**
     * @Route("/user/shop/{id}", name="user_shop")
     *
     * @return Response
     */
    public function userProductsForSaleAction(Request $request, User $user): Response
    {
        $products = $this->paginator->paginate(
            $this->getDoctrine()->getRepository(Product::class)
                ->findProductsByUserByQueryBuilder($user)
                ->andWhere('product.isListed = 1')
            ,
            $request->query->getInt('page', 1),
            6
        );

        return $this->render("@ShoppingCart/users/user_shop_page.html.twig", [
            'products' => $products,
            'seller' => $user,
        ]);
    }
}
