<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\ProductType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin/product")
 */
class ProductController extends Controller
{
    /**
     * Lists all Product entities.
     *
     * @Route("/", name="admin_product_index", defaults={"page" = 1})
     * @Route("/{page}", name="admin_product_index_paginated", requirements={"page" : "\d+"})
     * @Method("GET")
     */
    public function index($page) {
        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository(Product::class)->findAll();

        $paginator = $this->get('knp_paginator');
        $products = $paginator->paginate($query, $page, Product::NUM_ITEMS);
        $products->setUsedRoute('admin_product_index_paginated');

        return $this->render('admin/product/index.html.twig', array('products' => $products));
    }

    /**
     * 
     * @Route("/new/", name="admin_product_new")
     * @Method({"GET", "POST"})
     */
    public function create(Request $request) {

        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();
            $this->addFlash('success', $this->get('translator')->trans('msg.the_product')
                    . $product->getName()
                    . $this->get('translator')->trans('msg.product_created_successfully'));

            return $this->redirectToRoute('admin_product_index');
        }
        return $this->render('admin/product/new.html.twig', array('form' => $form->createView(),));
    }

    /**
     * Finds and displays a Product entity.
     *
     * @Route("/{id}/show", requirements={"id" = "\d+"}, name="admin_product_show")
     * @Method("GET")
     */
    public function show(Product $product) {

        return $this->render('admin/product/show.html.twig', array(
                    'product' => $product,
        ));
    }

    /**
     * Displays a form to edit an existing Product entity.
     *
     * @Route("/{id}/edit", requirements={"id" = "\d+"}, name="admin_product_edit")
     * @Method({"GET", "POST"})
     */
    public function edit(Product $product, Request $request) {
        $editForm = $this->createForm(ProductType::class, $product);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();
            $this->addFlash('success', $this->get('translator')->trans('msg.the_product')
                    . $product->getName()
                    . $this->get('translator')->trans('msg.product_updated_successfully'));

            return $this->redirectToRoute('admin_product_index');
        }

        return $this->render('admin/product/edit.html.twig', array(
                    'product' => $product,
                    'edit_form' => $editForm->createView(),
        ));
    }

}