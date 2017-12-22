<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Product;
use App\Form\CartType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/user/cart")
 */
class CartController extends Controller {

    /**
     * @Route("/", name="user_cart")
     * @Method("GET")
     */
    public function index() {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $cart = $em->getRepository(Cart::class)->findBy(array('user' => $user->getId()));

        return $this->render('front/cart/index.html.twig', array('products' => $cart));
    }

    /**
     * @Route("/add", name="cart_add")
     * @Method({"GET", "POST"})
     */
    public function addToCart(Request $request) {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $cart = new Cart();
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository(Product::class)
                ->findOneBy(array('id' => $request->query->get('id')));
        $cart->setProduct($product);
        $cart->setUser($user);
        
        $form = $this->createForm(CartType::class, $cart);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $product->setAmount($product->getAmount() - $cart->getQuantity());
            if($product->getAmount()>=0){
            $em->persist($cart);
            $em->flush();
            } else {
                throw new Exception('Insufficient availability!');
            }
            return $this->redirectToRoute('front_index');
        }
        return $this->render('front/cart/add.html.twig', array('form' => $form->createView(),));
    }

    /**
     * 
     * @param integer $id
     * @Route("/{id}/remove", requirements={"id" = "\d+"}, name="cart_remove")
     * @Method("GET")
     */
    public function removeFromCart($id) {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();

        $cart = $em->getRepository(Cart::class)->findOneBy(array('id' => $id, 'user' => $user));
        $productId = $cart->getProduct()->getId();
        $product = $em->getRepository(Product::class)->findOneBy(array('id' => $productId));
        
        $product->setAmount($product->getAmount() + $cart->getQuantity());
        $em->remove($cart);
        $em->flush();
        
        return $this->redirectToRoute('user_cart');
    }

}
