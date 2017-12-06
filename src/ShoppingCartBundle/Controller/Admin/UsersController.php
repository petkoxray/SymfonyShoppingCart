<?php

namespace ShoppingCartBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use ShoppingCartBundle\Entity\User;
use ShoppingCartBundle\Form\UserAddEditForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


/**
 * Class UsersController
 * @package ShoppingCartBundle\Controller
 *
 * @Route("/admin")
 * @Security("is_granted('ROLE_ADMIN')")
 */
class UsersController extends Controller
{

    /**
     * @Route("/users/all", name="admin_users_all")
     * @Method("GET")
     * @return Response
     */
    public function allUsersAction(): Response
    {
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();

        return $this->render("ShoppingCartBundle:admin/users:all.html.twig", [
            "users" => $users
        ]);
    }

    /**
     * @Route("/users/add", name="admin_users_add")
     *
     * @param Request $request
     * @return Response
     */
    public function addUserAction(Request $request): Response
    {
        $form = $this->createForm(UserAddEditForm::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash("success", "User {$user->getFullName()} added successfully!");

            return $this->redirectToRoute('admin_users_all');
        }

        return $this->render('@ShoppingCart/admin/users/add.html.twig', [
            "add_form" => $form->createView()
        ]);
    }

    /**
     * @Route("/users/delete/{id}", name="admin_users_delete")
     * @return Response
     */
    public function deleteUserAction(User $user): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();
        $this->addFlash("success", "User with username {$user->getEmail()} deleted successfully!");

        return $this->redirectToRoute('admin_users_all');
    }

    /**
     * @Route("/users/edit/{id}", name="admin_users_edit")
     *
     * @param Request $request
     * @param User $user
     * @return Response
     */
    public function editUserAction(Request $request, User $user): Response
    {
        $form = $this->createForm(UserAddEditForm::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash("success", "User {$user->getFullName()} edited successfully!");

            return $this->redirectToRoute('admin_users_all');
        }

        return $this->render('@ShoppingCart/admin/users/edit.html.twig', [
            "edit_form" => $form->createView()
        ]);
    }
}
