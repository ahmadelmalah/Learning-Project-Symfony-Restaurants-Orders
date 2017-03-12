<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
        ]);
    }

    /**
     * @Route("/test", name="admin")
     */
    public function testAction(Request $request)
    {
      if ($this->isGranted('ROLE_USER') == false) {
          return new Response( 'Who Are You?' );
      }
        $user = $this->getDoctrine()
        ->getRepository('AppBundle:User')
        ->find(1);
        return new Response($user->getUsername());
    }
}
