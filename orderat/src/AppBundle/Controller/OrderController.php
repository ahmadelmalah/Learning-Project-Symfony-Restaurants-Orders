<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Entity\Restaurant;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use AppBundle\Form\RestaurantType;

class OrderController extends Controller
{
    /**
     * @Route("/active", name="active")
     */
    public function indexAction(Request $request)
    {
        if ($this->isGranted('ROLE_USER') == true) {
          return $this->render('default/index.html.twig', [
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
          //code here
           $restaurant = new Restaurant();
           $restaurant->setName('KFC');

          $form = $this->createFormBuilder($restaurant)
                  ->add('name', TextType::class)
                  ->add('save', SubmitType::class, array('label' => 'Create Restaurant'))
                  ->getForm();

          //page
          return $this->render('default/index.html.twig', [
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
