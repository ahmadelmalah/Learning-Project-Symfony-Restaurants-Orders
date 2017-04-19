<?php

namespace AppBundle\Controller;
//Framework
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\{Request, Response, RedirectResponse};
//External Bundles
use FOS\RestBundle\Controller\FOSRestController;

class DefaultController extends FOSRestController
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        //Redirect to active if the visitor is user
        if ($this->isGranted('ROLE_USER') == true) {
          return new RedirectResponse($this->generateUrl('active'));
        }

        return $this->render('default/content/visitor/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
        ]);
    }
}
