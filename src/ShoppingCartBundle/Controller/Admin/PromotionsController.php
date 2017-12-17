<?php

namespace ShoppingCartBundle\Controller\Admin;

use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use ShoppingCartBundle\Entity\Promotion;
use ShoppingCartBundle\Form\AddPromotionToAllProducts;
use ShoppingCartBundle\Form\AddPromotionToCategoryForm;
use ShoppingCartBundle\Form\PromotionAddEditForm;
use ShoppingCartBundle\Service\PromotionServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
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
    private $promotionService;

    public function __construct(PromotionServiceInterface $promotionService)
    {
        $this->promotionService = $promotionService;
    }

    /**
     * @Route("/promotions", name="admin_promotions_all")
     *
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function allPromotionsAction(Request $request, PaginatorInterface $paginator): Response
    {
        $promotions = $paginator->paginate(
            $this->getDoctrine()->getRepository(Promotion::class)
            ->findAllByQueryBuilder(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render("@ShoppingCart/admin/promotions/all.html.twig", [
            "promotions" => $promotions
        ]);
    }

    /**
     * @Route("/promotions/add", name="admin_promotions_add")
     *
     * @param Request $request
     * @return Response
     */
    public function addPromotionAction(Request $request): Response
    {
        $form = $this->createForm(PromotionAddEditForm::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $promotion = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($promotion);
            $em->flush();

            $this->addFlash("success", "Promotion added successfully!");
            return $this->redirectToRoute("admin_promotions_all");
        }

        return $this->render("@ShoppingCart/admin/promotions/add.html.twig", [
            "add_form" => $form->createView()
        ]);
    }

    /**
     * @Route("/promotions/edit/{id}", name="admin_promotions_edit")
     *
     * @param Promotion $promotion
     * @param Request $request
     * @return Response
     */
    public function editPromotionAction(Request $request, Promotion $promotion): Response
    {
        $form = $this->createForm(PromotionAddEditForm::class, $promotion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $promotion = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash("success", "Promotion edited successfully!");
            return $this->redirectToRoute("admin_promotions_all");
        }

        return $this->render("@ShoppingCart/admin/promotions/edit.html.twig", [
            "edit_form" => $form->createView()
        ]);
    }

    /**
     * @Route("/promotions/delete/{id}", name="admin_promotions_delete")
     *
     * @param Promotion $promotion
     * @return Response
     */
    public function deletePromotionAction(Promotion $promotion): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($promotion);
        $em->flush();

        $this->addFlash("success", "Promotion deleted successfully");
        return $this->redirectToRoute("admin_promotions_all");
    }

    /**
     * @Route("/promotions/add-to-category/", name="admin_promotions_apply_to_category")
     *
     * @param Request
     * @return Response
     */
    public function applyPromotionToCategoryAction(Request $request): Response
    {
        $form = $this->createForm(AddPromotionToCategoryForm::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $this->promotionService->applyPromotionToCategory(
                $data['promotion'], $data['category']
            );

            return $this->redirectToRoute('admin_promotions_all');
        }

        return $this->render("@ShoppingCart/admin/promotions/add-promotion-to-category.html.twig", [
            "add_form" => $form->createView()
        ]);
    }

    /**
     * @Route("/promotions/add-to-all-products/",
     *     name="admin_promotions_apply_to_all_products")
     *
     * @param Request
     * @return Response
     */
    public function applyPromotionToAllProductsAction(Request $request): Response
    {
        $form = $this->createForm(AddPromotionToAllProducts::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $this->promotionService->applyPromotionToAllProducts(
                $data['promotion']
            );

            $this->redirectToRoute('admin_promotions_all');
        }

        return $this->render('@ShoppingCart/admin/promotions/add-promotion-to-all-products.html.twig', [
            "add_form" => $form->createView()
        ]);
    }
}
