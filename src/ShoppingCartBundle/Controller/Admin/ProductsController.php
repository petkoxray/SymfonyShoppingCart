<?php

namespace ShoppingCartBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use ShoppingCartBundle\Entity\Product;
use ShoppingCartBundle\Form\ProductAddEditForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


/**
 * Class ProductsController
 * @package ShoppingCartBundle\Controller\Admin
 *
 * @Security("is_granted('ROLE_EDITOR')")
 * @Route("/admin")
 */
class ProductsController extends Controller
{
    /**
     * @Route("/products/", name="admin_products_all")
     * @Method("GET")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function allProductsAction(Request $request): Response
    {
        $paginator = $this->get('knp_paginator');
        $products = $paginator->paginate(
            $this->getDoctrine()->getRepository(Product::class)
                ->findAllByQueryBuilder(),
            $request->query->getInt('page', 1),
            9
        );

        return $this->render('@ShoppingCart/admin/products/all.html.twig', [
            "products" => $products
        ]);
    }

    /**
     * @Route("/products/add", name="admin_products_add")
     *
     * @param Request $request
     * @return Response
     */
    public function addProductsAction(Request $request): Response
    {
        $form = $this->createForm(ProductAddEditForm::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            $this->addFlash("success", "Product {$product->getName()} added successfully!");
            return $this->redirectToRoute("admin_products_all");
        }

        return $this->render("@ShoppingCart/admin/products/add.html.twig", [
            "add_form" => $form->createView()
        ]);
    }

    /**
     * @Route("/products/edit/{slug}", name="admin_products_edit")
     *
     * @param Request $request
     * @param Product $product
     * @return Response
     */
    public function editProductsAction(Request $request, Product $product): Response
    {
        $form = $this->createForm(ProductAddEditForm::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash("success", "Product {$product->getName()} edited successfully!");
            return $this->redirectToRoute("admin_products_all");
        }

        return $this->render("@ShoppingCart/admin/products/edit.html.twig", [
            "edit_form" => $form->createView()
        ]);
    }

    /**
     * @Route("/products/delete/{slug}", name="admin_products_delete")
     *
     * @param Product $product
     * @return Response
     */
    public function deleteProductsAction(Product $product): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($product);
        $em->flush();

        $this->addFlash("success", "Product {$product->getName()} was deleted successfully!");
        return $this->redirectToRoute('admin_products_all');
    }
}
