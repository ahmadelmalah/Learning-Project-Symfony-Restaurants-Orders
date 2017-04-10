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
     * @Route("/ajax/test", name="ajaxtest")
     */
    public function testAction(Request $request)
    {
        return new Response( 'testsdsr v0' );
    }
}
