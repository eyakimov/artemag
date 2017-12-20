<?php

namespace App\Controller;

use App\Entity\Product;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller {

     /**
     * Lists all Product entities.
     *
     * @Route("/", name="front_index", defaults={"page" = 1})
     * @Route("/{page}", name="front_product_index_paginated", requirements={"page" : "\d+"})
     * @Method("GET")
     */
    public function index($page) {
        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository(Product::class)->findAll();

        $paginator = $this->get('knp_paginator');
        $products = $paginator->paginate($query, $page, Product::NUM_ITEMS);
        $products->setUsedRoute('front_product_index_paginated');

        return $this->render('front/product/index.html.twig', array('products' => $products));
    }
    
     /**
     * Finds and displays a Product entity.
     *
     * @Route("/{id}/show", requirements={"id" = "\d+"}, name="front_product_show")
     * @Method("GET")
     */
    public function show(Product $product) {

        return $this->render('front/product/show.html.twig', array(
                    'product' => $product,
        ));
    }
}
