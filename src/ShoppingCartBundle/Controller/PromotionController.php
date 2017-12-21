<?php

namespace ShoppingCartBundle\Controller;

use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ShoppingCartBundle\Entity\Promotion;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PromotionController extends Controller
{
    private $paginator;

    public function __construct(PaginatorInterface $paginator)
    {
        $this->paginator = $paginator;
    }

    /**
     * @Route("/promotions/all", name="promotions_all")
     *
     * @return Response
     */
    public function allPromotionsAction(Request $request): Response
    {
        $promotions = $this->paginator->paginate(
            $this->getDoctrine()->getRepository(Promotion::class)
            ->findAllAvailableByQueryBuilder(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('@ShoppingCart/promotions/all.html.twig', [
            'promotions' => $promotions
        ]);
    }


    /**
     * @Route("/promotions/{id}/products/", name="promotion_show_products")
     *
     * @param Request $request
     * @return Response
     */
    public function showPromotionProductsAction(Request $request, Promotion $promotion): Response
    {
        if (!$promotion->isActive()) {
            throw new NotFoundHttpException();
        }

        $products = $this->paginator->paginate(
            $promotion->getProducts(),
            $request->query->getInt('page', 1),
            9
        );

        return $this->render('@ShoppingCart/promotions/show_promotion_products.html.twig', [
           'products' => $products
        ]);
    }
}
