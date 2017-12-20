<?php

namespace ShoppingCartBundle\Controller\Admin;

use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use ShoppingCartBundle\Entity\Order;
use ShoppingCartBundle\Service\OrderServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class UsersController
 * @package ShoppingCartBundle\Controller
 *
 * @Route("/admin/orders")
 * @Security("is_granted('ROLE_ADMIN')")
 */
class OrdersController extends Controller
{
    /**
     * @Route("/all", name="admin_orders_all")
     *
     * @return Response
     */
    public function allOrdersAction(Request $request, PaginatorInterface $paginator): Response
    {
        $orders = $paginator->paginate(
            $this->getDoctrine()->getRepository(Order::class)
                ->findAllByQueryBuilder(),
            $request->query->getInt('page', 1),
            7
        );
        return $this->render('@ShoppingCart/admin/orders/all.html.twig',[
            "orders" => $orders
        ]);
    }

    /**
     * @Route("/complete/{id}", name="admin_order_complete  ")
     *
     * @param Order $order
     * @return Response
     */
    public function completeOrderAction(Order $order, OrderServiceInterface $orderService): Response
    {
        $orderService->completeOrder($order);
        return $this->redirectToRoute('admin_orders_all');
    }
}
