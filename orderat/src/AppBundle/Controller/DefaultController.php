<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        if ($this->isGranted('ROLE_USER') == false) {
          return $this->render('visitor/index.html.twig', [
              'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
          ]);
        }

        return new RedirectResponse($this->generateUrl('active'));
    }

    /**
     * @Route("/admin/test", name="test")
     */
    public function testAction(Request $request)
    {
        $user = $this->getDoctrine()
        ->getRepository('AppBundle:Restaurant')
        ->find(2);
        return new Response( 'test' );
    }
}
