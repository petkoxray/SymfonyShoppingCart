<?php

namespace ShoppingCartBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use ShoppingCartBundle\Entity\Category;
use ShoppingCartBundle\Form\CategoryAddEditForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class CategoriesController
 * @package ShoppingCartBundle\Controller
 *
 * @Route("/admin")
 * @Security("is_granted('ROLE_EDITOR')")
 */
class CategoriesController extends Controller
{
    /**
     * @Route("/categories", name="admin_categories_all")
     *
     * @param Request $request
     * @return Response
     */
    public function listCategoriesAction(Request $request): Response
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();

        return $this->render("@ShoppingCart/admin/categories/all.html.twig", [
            "categories" => $categories
        ]);
    }

    /**
     * @Route("/categories/add", name="admin_categories_add")
     *
     * @param Request $request
     * @return Response
     */
    public function addCategoryAction(Request $request): Response
    {
        $form = $this->createForm(CategoryAddEditForm::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            $this->addFlash("success", "Category {$category->getName()} was added!");
            return $this->redirectToRoute("admin_categories_all");
        }

        return $this->render("@ShoppingCart/admin/categories/add.html.twig", [
            "add_form" => $form->createView()
        ]);
    }

    /**
     * @Route("/categories/edit/{slug}", name="admin_categories_edit")
     *
     * @param Request $request
     * @param Category $category
     * @return Response
     */
    public function editCategoryAction(Request $request, Category $category): Response
    {
        $form = $this->createForm(CategoryAddEditForm::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash("success", "Category {$category->getName()} was updated!");
            return $this->redirectToRoute("admin_categories_all");
        }

        return $this->render("@ShoppingCart/admin/categories/add.html.twig", [
            "add_form" => $form->createView()
        ]);
    }

    /**
     * @Route("/categories/delete/{id}", name="admin_categories_delete")
     * @Method("POST")
     *
     * @param Category $category
     * @return Response
     */
    public function deleteCategoryAction(Category $category): Response
    {
        if ($category->getProducts()->count() > 0) {
            $this->addFlash("danger", "Category is not empty. You cant delete category with products!");
            return $this->redirectToRoute("admin_categories_all");
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($category);
        $em->flush();

        $this->addFlash("success", "Category {$category->getName()} was deleted!");
        return $this->redirectToRoute("admin_categories_all");
    }
}