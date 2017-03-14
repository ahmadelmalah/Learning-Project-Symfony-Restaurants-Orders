<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Entity\Restaurant;
use AppBundle\Entity\Forder;
use AppBundle\Entity\Item;

use AppBundle\Form\RestaurantType;
use AppBundle\Form\ForderType;
use AppBundle\Form\ItemType;

class OrderController extends Controller
{
    /**
     * @Route("/active", name="active")
     */
    public function indexAction(Request $request)
    {
        if ($this->isGranted('ROLE_USER') == false) {
          return new Response('NOT AUTHORIZED');
        }

        $forders = $this->get('app.OrderService')->getOrders();
        return $this->render('default/content/active.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'forders' => $forders
        ]);
    }

    /**
     * @Route("/new", name="new")
     */
    public function newAction(Request $request)
    {
        if ($this->isGranted('ROLE_USER') == false) {
          return new Response('NOT AUTHORIZED');
        }

       $forder = new Forder();
       $form = $this->createForm(ForderType::class, $forder);
       $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('app.OrderService')->create($forder);
            return $this->redirectToRoute('active');
        }

        return $this->render('default/content/new.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/orders/{id}/add", name="addItems")
     */
    public function addAction(Request $request, $id)
    {
        if ($this->isGranted('ROLE_USER') == false) {
          return new Response('NOT AUTHORIZED');
        }

       $item = new Item();
       $form = $this->createForm(ItemType::class, $item);
       $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('app.ItemService')->create($item, $id);
            return $this->redirectToRoute('active');
        }

        return $this->render('default/content/order/add.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/orders/{id}/show", name="showOrder")
     */
    public function showAction(Request $request, $id)
    {
        if ($this->isGranted('ROLE_USER') == false) {
          return new Response('NOT AUTHORIZED');
        }
        $forder = $this->getDoctrine()
        ->getRepository('AppBundle:Forder')
        ->find($id);
        return $this->render('default/content/order/show.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'forder' => $forder
        ]);
    }

    /**
     * @Route("/orders/{id}/ready", name="readyOrder")
     */
    public function readyAction(Request $request, $id)
    {
        if ($this->isGranted('ROLE_USER') == false) {
          return new Response('NOT AUTHORIZED');
        }
        $this->get('app.OrderService')->ready($id);
        $forder = $this->getDoctrine()
        ->getRepository('AppBundle:Forder')
        ->find($id);
        return $this->render('default/content/order/show.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'forder' => $forder
        ]);
    }

    /**
     * @Route("/orders/{id}/call", name="callOrder")
     */
    public function callAction(Request $request, $id)
    {
        if ($this->isGranted('ROLE_USER') == false) {
          return new Response('NOT AUTHORIZED');
        }
        $this->get('app.OrderService')->call($id);
        $forder = $this->getDoctrine()
        ->getRepository('AppBundle:Forder')
        ->find($id);
        return $this->render('default/content/order/show.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'forder' => $forder
        ]);
    }

    /**
     * @Route("/orders/{id}/deliver", name="deliverOrder")
     */
    public function deliverAction(Request $request, $id)
    {
        if ($this->isGranted('ROLE_USER') == false) {
          return new Response('NOT AUTHORIZED');
        }
        $this->get('app.OrderService')->deliver($id);
        $forder = $this->getDoctrine()
        ->getRepository('AppBundle:Forder')
        ->find($id);
        return $this->render('default/content/order/show.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'forder' => $forder
        ]);
    }

    /**
     * @Route("/orders/{id}/complete", name="completeOrder")
     */
    public function completeAction(Request $request, $id)
    {
        if ($this->isGranted('ROLE_USER') == false) {
          return new Response('NOT AUTHORIZED');
        }
        $this->get('app.OrderService')->complete($id);
        $forder = $this->getDoctrine()
        ->getRepository('AppBundle:Forder')
        ->find($id);
        return $this->render('default/content/order/show.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'forder' => $forder
        ]);
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
