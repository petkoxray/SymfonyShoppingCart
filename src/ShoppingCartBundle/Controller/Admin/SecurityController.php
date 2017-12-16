<?php

namespace ShoppingCartBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use ShoppingCartBundle\Entity\BannedIP;
use ShoppingCartBundle\Form\BannedIPAddForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class UsersController
 * @package ShoppingCartBundle\Controller
 *
 * @Route("/admin/security")
 * @Security("is_granted('ROLE_ADMIN')")
 */
class SecurityController extends Controller
{
    /**
     * @Route("/ips/all", name="admin_bannedips_all")
     * @return Response
     */
    public function allBannedAction(): Response
    {
        $bannedIps = $this->getDoctrine()->getRepository(BannedIP::class)->findAll();

        return $this->render("@ShoppingCart/admin/security/blacklist_ips/all.html.twig",[
            "bannedIps" => $bannedIps
        ]);
    }

    /**
     * @Route("/ips/add", name="admin_bannedips_add")
     *
     * @param Request
     * @return Response
     */
    public function addBannedIpAction(Request $request): Response
    {
        $form = $this->createForm(BannedIPAddForm::class);
        $form = $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bannedIP = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($bannedIP);
            $em->flush();

            $this->addFlash("success", "Ip {$bannedIP->getIp()} added to the banned ips.");
            return $this->redirectToRoute("admin_bannedips_all");
        }

        return $this->render("@ShoppingCart/admin/security/blacklist_ips/add.html.twig", [
           "add_form" => $form->createView()
        ]);
    }

    /**
     * @Route("/ips/delete/{id}", name="admin_bannedips_delete")
     *
     * @param BlackList
     * @return Response
     */
    public function deleteBannedIpAction(BannedIP $bannedIP): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($bannedIP);
        $em->flush();

        $this->addFlash("success", "Ip {$bannedIP->getIp()} successfully removed from blacklist.");
        return $this->redirectToRoute("admin_bannedips_all");
    }
}