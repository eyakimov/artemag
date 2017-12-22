<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * @Route("/admin/category")
 */
class CategoryController extends Controller {

    /**
     * @Route("/", name="admin_category_index")
     * @Method("GET")
     */
    public function index() {
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository(Category::class)->findAll();

        return $this->render('admin/category/index.html.twig', array('categories' => $categories));
    }

    /**
     * @Route("/new", name="admin_category_new")
     * @Method({"GET", "POST"})
     */
    public function create(Request $request) {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('admin_category_index');
        }
        return $this->render('admin/category/new.html.twig', array('form' => $form->createView(),));
    }
    /**
     * @Route("/{id}/show", requirements={"id" = "\d+"}, name="admin_category_show")
     * @Method("GET")
     */
    public function show(Category $category) {

        return $this->render('admin/category/show.html.twig', array(
                    'category' => $category,
        ));
    }

    /**
     * @Route("/{id}/edit", requirements={"id" = "\d+"}, name="admin_category_edit")
     * @Method({"GET", "POST"})
     */
    public function edit(Category $category, Request $request) {

        $editForm = $this->createForm(CategoryType::class, $category);
        $editForm->handleRequest($request);
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();
            
            return $this->redirectToRoute('admin_category_index');
        }

        return $this->render('admin/category/edit.html.twig', array(
                    'category' => $category,
                    'edit_form' => $editForm->createView(),
        ));
    }
}
