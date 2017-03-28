<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

class DefaultController extends FOSRestController
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        if ($this->isGranted('ROLE_USER') == false) {
          return $this->render('default/content/visitor/index.html.twig', [
              'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
          ]);
        }

        return new RedirectResponse($this->generateUrl('active'));
    }

    /**
     * @Route("/test55", name="test55")
     */
    public function testAction(Request $request)
    {
        $user = $this->getDoctrine()
        ->getRepository('AppBundle:Restaurant')
        ->find(2);
          //  $view = $this->view($user);
          // $view->setFormat('json');
          // return $this->handleView($view);
        return new Response( $user->getName() . 'o' );
    }
}
