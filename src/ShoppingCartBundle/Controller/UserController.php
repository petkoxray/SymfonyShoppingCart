<?php

namespace ShoppingCartBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use ShoppingCartBundle\Form\ProfileEditForm;
use ShoppingCartBundle\Form\RegisterForm;
use ShoppingCartBundle\Entity\Role;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ShoppingCartBundle\Entity\User;

class UserController extends Controller
{
    /**
     * @Route("/register", name="user_register")
     * @Method("GET")
     */
    public function registerAction(): Response
    {
        if ($this->get("security.authorization_checker")->isGranted("ROLE_USER")) {
            return $this->redirectToRoute("homepage");
        }

        $form = $this->createForm(RegisterForm::class);

        return $this->render("@ShoppingCart/users/register.html.twig", [
            "register_form" => $form->createView()
        ]);
    }

    /**
     * @Route("/register", name="user_register_process")
     * @Method("POST")
     * @param Request $request
     * @return Response
     */
    public function registerProcessAction(Request $request): Response
    {
        if ($this->get("security.authorization_checker")->isGranted("ROLE_USER")) {
            return $this->redirectToRoute("homepage");
        }

        $user = new User();
        $em = $this->getDoctrine()->getManager();
        $userRole = $em->getRepository(Role::class)
            ->findOneBy(["name" => "ROLE_USER"]);

        $user->addRole($userRole);

        $form = $this->createForm(RegisterForm::class, $user);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $user = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->container->get("session")->getFlashBag()
                ->add("success", "You have successfully registered and logged in.");

            return $this->get("security.authentication.guard_handler")
                ->authenticateUserAndHandleSuccess(
                    $user,
                    $request,
                    $this->get("shopping_cart.security.login_form_authenticator"),
                    "main"
                );
        }

        return $this->render("@ShoppingCart/users/register.html.twig", [
            "register_form" => $form->createView()
        ]);
    }

    /**
     * @Route("/profile", name="user_profile")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function profileAction(): Response
    {
        $user = $this->getUser();

        return $this->render("@ShoppingCart/users/profile.html.twig", [
            "user" => $user
        ]);
    }

    /**
     * @Route("/profile/edit", name="user_profile_edit")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param Request $request
     * @return Response
     */
    public function profileEditAction(Request $request): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(ProfileEditForm::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash("success", "Profile edited successfully!");

            return $this->redirectToRoute("user_profile");
        }

        return $this->render('ShoppingCartBundle:users:edit.html.twig', [
           "edit_form" => $form->createView()
        ]);
    }
}