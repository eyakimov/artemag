<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller {

    /**
     * Lists all Product entities.
     *
     * @Route("/", name="front_index", defaults={"page" = 1})
     * @Route("/{page}", name="front_product_index_paginated", requirements={"page" : "\d+"})
     * @Method("GET")
     */
    public function index($page) {
        $u = $this->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        
        $user=$em->getRepository(User::class)->findOneBy(array('username'=>$u));
        if(!null===$user){
        $user->setLastVisited(new \DateTime());
        $em->flush();
        }
        $query = $em->getRepository(Product::class)->findGreaterByAmount(0);

        $paginator = $this->get('knp_paginator');
        $products = $paginator->paginate($query, $page, Product::NUM_ITEMS);
        $products->setUsedRoute('front_product_index_paginated');

        return $this->render('front/product/index.html.twig', array('products' => $products));
    }

    /**
     * Lists all Product entities by category.
     *
     * @Route("/products/{category}", name="front_index_by_category", defaults={"page" = 1})
     * @Route("/products/{category}/{page}", name="front_product_by_category_index_paginated", requirements={"page" : "\d+"})
     * @Method("GET")
     */
    public function indexByCategory($category, $page) {
        $em = $this->getDoctrine()->getManager();
        $cat = $em->getRepository(Category::class)->findOneBy(array('name' => $category));
        $categoryId = $cat->getId();
        $query = $em->getRepository(Product::class)->findByCategory($categoryId);

        $paginator = $this->get('knp_paginator');
        $products = $paginator->paginate($query, $page, Product::NUM_ITEMS);
        $products->setUsedRoute('front_product_by_category_index_paginated');

        return $this->render('front/product/indexByCategory.html.twig', array('products' => $products));
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
