<?php

namespace ShoppingCartBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
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
        /** @var User $currentUser */
        $currentUser = $this->getUser();

        return $this->render("@ShoppingCart/users/profile.html.twig", [
            "user" => $currentUser
        ]);
    }
}