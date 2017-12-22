<?php

namespace App\Controller;

use App\Form\UserType;
use App\Form\ChangePasswordType;
use App\Form\Model\ChangePassword;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Controller used to manage the application security.
 * See http://symfony.com/doc/current/cookbook/security/form_login_setup.html.
 * 
 */
class SecurityController extends Controller {

    /**
     * @Route("/login", name="security_login")
     */
    public function loginAction(Request $request, AuthenticationUtils $authUtils) {
        // get the login error if there is one
        $error = $authUtils->getLastAuthenticationError();
        
        // last username entered by the user
        $lastUsername = $authUtils->getLastUsername();

        return $this->render('security/login.html.twig', array(
                    'last_username' => $lastUsername,
                    'error' => $error,
        ));
    }
//
//    /**
//     * This is the route the login form submits to.
//     *
//     * But, this will never be executed. Symfony will intercept this first
//     * and handle the login automatically. See form_login in app/config/security.yml
//     *
//     * @Route("/login_check", name="security_login_check")
//     */
//    public function loginCheckAction() {
//
//        throw new Exception('This should never be reached!');
//    }

    /**
     * This is the route the user can use to logout.
     *
     * But, this will never be executed. Symfony will intercept this first
     * and handle the logout automatically. See logout in app/config/security.yml
     *
     * @Route("/logout", name="security_logout")
     */
    public function logoutAction() {
        throw new Exception('This should never be reached!');
    }

    /**
     * 
     * @Route("/user/change_password", name="change_password")
     */
    public function changePasswordAction(Request $request) {
        $changePasswordModel = new ChangePassword();
        $form = $this->createForm(ChangePasswordType::class, $changePasswordModel);
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($this->get('security.password_encoder')
                            ->encodePassword($user, $form->getData()->getNewPassword()));
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $this->addFlash('success', "Password change successfully! You need logoff, then log in again.");

            return $this->render('admin/user/_change_password.html.twig', array(
                        'cp_form' => $form->createView(),
            ));
        }

        return $this->render('admin/user/_change_password.html.twig', array(
                    'cp_form' => $form->createView(),
        ));
    }

    /**
     * 
     * @Route("/user/profile", name="user_profile")
     */
    public function profileAction(Request $request) {

        $user = $this->get('security.token_storage')->getToken()->getUser();

        $profileForm = $this->createForm(UserType::class, $user);
        $profileForm->handleRequest($request);

        if ($profileForm->isSubmitted() && $profileForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'user.updated_successfully');

            return $this->redirectToRoute('user_profile');
        }

        return $this->render('admin/user/_profile.html.twig', array(
                    'user' => $user,
                    'up_form' => $profileForm->createView(),
        ));
    }

}
