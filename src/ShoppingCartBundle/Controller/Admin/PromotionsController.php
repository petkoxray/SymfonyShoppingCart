<?php

namespace ShoppingCartBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class PromotionsController
 * @package ShoppingCartBundle\Controller\Admin
 *
 * @Route("/admin")
 * @Security("is_granted('ROLE_EDITOR')")
 */
class PromotionsController extends Controller
{
    /**
     * @Route("/promotions", name="admin_promotions_all")
     *
     * @return Response
     */
    public function allPromotionsAction(): Response
    {
        return $this->render("@ShoppingCart/admin/promotions/all.html.twig");
    }
}
