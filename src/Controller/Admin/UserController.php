<?php

namespace App\Controller\Admin;

use App\Entity\Group;
use App\Entity\User;
use App\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/superadmin")
 */
class UserController extends Controller {

    /**
     * Lists all User entities.
     *
     * @Route("/", name="admin_user_index", defaults={"page" = 1})
     * @Route("/{page}", name="admin_user_index_paginated", requirements={"page" : "\d+"})
     * @Method("GET")
     */
    public function indexAction($page) {
        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository(User::class)->findAll();

        $paginator = $this->get('knp_paginator');
        $users = $paginator->paginate($query, $page, User::NUM_ITEMS);
        $users->setUsedRoute('admin_user_index_paginated');

        return $this->render('admin/user/index.html.twig', array('users' => $users));
    }

    /**
     * 
     * @Route("/new/", name="admin_user_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request) {

        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form
                ->add('group', EntityType::class, array(
                    'class' => Group::class,
                    'choice_label' => 'name',
                ))
                ->add('plainPassword', RepeatedType::class, array(
                    'type' => PasswordType::class,
                    'first_options' => array('label' => 'Password'),
                    'second_options' => array('label' => 'Repeat Password'),
                ))
                ->add('isEnabled', CheckboxType::class, array(
                    'attr' => array('checked' => true,),
                ))
                ->add('saveAndCreateNew', SubmitType::class)
        ;

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $this->get('security.password_encoder')
                    ->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $user->setRegisteredAt(new \DateTime);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'User created successfully');

            return $this->redirectToRoute('admin_user_index');
        }
        return $this->render('admin/user/new.html.twig', array('form' => $form->createView(),));
    }

    /**
     * Finds and displays a User entity.
     *
     * @Route("/{id}/show", requirements={"id" = "\d+"}, name="admin_user_show")
     * @Method("GET")
     */
    public function showAction(User $user) {

        return $this->render('admin/user/show.html.twig', array(
                    'user' => $user,
        ));
    }

    /**
     * Displays a form to edit an existing User entity.
     *
     * @Route("/{id}/edit", requirements={"id" = "\d+"}, name="admin_user_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(User $user, Request $request) {
        $editForm = $this->createForm(UserType::class, $user);
        $editForm
                ->add('group', EntityType::class, array(
                    'class' => Group::class,
                    'choice_label' => 'name',
                ))
                ->add('isEnabled', CheckboxType::class)
        ;

        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $this->addFlash('success', 'user.updated_successfully');

            return $this->redirectToRoute('admin_user_index', array('id' => $user->getId()));
        }

        return $this->render('admin/user/edit.html.twig', array(
                    'user' => $user,
                    'edit_form' => $editForm->createView(),
        ));
    }

}
