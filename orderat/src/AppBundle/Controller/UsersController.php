<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UsersController extends FOSRestController
{
  /**
  * @Route("/test", name="test")
  * @Route("/api/test", name="testok")
   */
    public function getUsersAction(Request $request)
    {
        // $user = $this->getDoctrine()
        // ->getRepository('AppBundle:Restaurant')
        // ->findAll();
        //
        // $paginator  = $this->get('knp_paginator');
        // $pagination = $paginator->paginate(
        //     $user,
        //     $request->query->getInt('page', 2),
        //     3
        // );
        //return new Response('dome');
        return $this->render('test.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            // 'pagination' => $pagination
        ]);

        // parameters to template
        return $this->render('AcmeMainBundle:Article:list.html.twig', array('pagination' => $pagination));

        $str = array();
        foreach ($user as $key => $value) {
          $str[] = $value->getName();
        }

        $view = $this->view($str, 200);
        $view->setFormat('json');
        return $this->handleView($view);
        //return new Response($user->getName() . '3');

    }

    public function redirectAction()
    {
        $view = $this->redirectView($this->generateUrl('some_route'), 301);
        // or
        $view = $this->routeRedirectView('some_route', array(), 301);

        return $this->handleView($view);
    }
}
