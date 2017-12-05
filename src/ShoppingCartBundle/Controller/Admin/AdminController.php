<?php

namespace ShoppingCartBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;


/**
 * Class AdminController
 * @package ShoppingCartBundle\Controller\Admin
 *
 * @Security("is_granted('ROLE_EDITOR')")
 */
class AdminController extends Controller
{

    /**
     * @return Response
     *
     * @Route("/admin", name="admin_homepage")
     */
    public function indexAction(): Response
    {
        return $this->render('@ShoppingCart/admin/home.html.twig');
    }
}
