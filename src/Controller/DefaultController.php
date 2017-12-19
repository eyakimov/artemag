<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller {

    /**
     * @Route("/", name="homepage")
     */
    public function index() {
        return $this->render('base.html.twig', 
                ['path' => str_replace($this->getParameter('kernel.project_dir') . '/', '', __FILE__)]);
    }

}
