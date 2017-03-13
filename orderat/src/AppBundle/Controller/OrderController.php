<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Entity\Restaurant;
use AppBundle\Entity\Forder;

use AppBundle\Form\RestaurantType;
use AppBundle\Form\ForderType;

class OrderController extends Controller
{
    /**
     * @Route("/active", name="active")
     */
    public function indexAction(Request $request)
    {
        if ($this->isGranted('ROLE_USER') == true) {
          return $this->render('default/content/active.html.twig', [
              'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
          ]);
        }
    }

    /**
     * @Route("/new", name="new")
     */
    public function newAction(Request $request)
    {
        if ($this->isGranted('ROLE_USER') == true) {
           $forder = new Forder();
           $form = $this->createForm(ForderType::class, $forder);
           $form->handleRequest($request);
          if ($form->isSubmitted() && $form->isValid()) {

                  //Initial Values for order --> order serice
                  $state = $this->getDoctrine()->getRepository('AppBundle:state')->find(1);

                  $forder->setState($state);
                  $forder->setUser($user);
                  $forder->setCreatedAt(new \DateTime("now"));

                  $this->get('app.helper')->Save($forder);
                  return $this->redirectToRoute('new');
          }

          //page
          return $this->render('default/content/new.html.twig', [
              'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
              'form' => $form->createView(),
          ]);
        }
    }

    /**
     * @Route("/restaurants/new", name="restaurants-new")
     */
    public function newRestaurantAction(Request $request)
    {
        if ($this->isGranted('ROLE_USER') == true) {
          //code here
          $restaurant = new Restaurant();
          $form = $this->createForm(RestaurantType::class, $restaurant);
          $form->handleRequest($request);

          if ($form->isSubmitted() && $form->isValid()) {

                  $em = $this->getDoctrine()->getManager();
                  $em->persist($restaurant);
                  $em->flush();

                  return new Response('posted');
                  return $this->redirectToRoute('/');
              }

          return $this->render('default/index.html.twig', [
              'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
              'form' => $form->createView(),
          ]);
        }
    }
}
