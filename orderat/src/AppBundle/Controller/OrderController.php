<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Entity\Restaurant;
use AppBundle\Entity\Forder;
use AppBundle\Entity\Item;

use AppBundle\Form\RestaurantType;
use AppBundle\Form\ForderType;
use AppBundle\Form\ItemType;
use AppBundle\Form\FilterType;

class OrderController extends Controller
{
    /**
     * @Route("/active", name="active")
     * @Route("/archive", name="archive")
     */
    public function showOrdersAction(Request $request)
    {
        $form = $this->createForm(FilterType::class, array('n' => 'name'));
        $form->handleRequest($request);

        // $filter_restaurant = null;
        // // if ($form->isSubmitted() && $form->isValid()) {
        // //     $filter_restaurant = $form->getData()['restaurant']->getID();
        // // }
        // $x = null;
        // $x = ;
        // return new Response($x['restaurant']);

        $forders = $this->get('app.OrderService')->getOrders(
          $request->get('_route'),
          $request->query->getInt('page', 1),
          $request->query->get('filter')
          //$filter_restaurant
        );

        return $this->render('default/content/active.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'forders' => $forders,
            'form_filter' => $form->createView(),
        ]);
    }

    /**
     * @Route("/new", name="new")
     */
    public function newAction(Request $request)
    {
       $forder = new Forder();
       $form = $this->createForm(ForderType::class, $forder);
       $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('app.OrderService')->create($forder);
            return $this->redirectToRoute('showOrder', array('id' => $forder->getID()));
        }

        return $this->render('default/content/new.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/orders/{id}/add", name="addItems")
     * @ParamConverter("forder", class="AppBundle:Forder")
     */
    public function addAction(Request $request, $forder)
    {
       $item = new Item();
       $form = $this->createForm(ItemType::class, $item);
       $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('app.ItemService')->create($item, $forder);
            return $this->redirectToRoute('active');
        }

        return $this->render('default/content/order/add.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/orders/{id}/show", name="showOrder")
     * @ParamConverter("forder", class="AppBundle:Forder")
     */
    public function showAction(Request $request, $forder)
    {
        return $this->render('default/content/order/show.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'forder' => $forder
        ]);
    }

    /**
     * @Route("/orders/{id}/ready", name="readyOrder")
     * @ParamConverter("forder", class="AppBundle:Forder")
     */
    public function readyAction(Request $request, $forder)
    {
        $this->get('app.OrderService')->changeOrderState($forder, 2); //change it to ready
        return $this->render('default/content/order/show.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'forder' => $forder
        ]);
    }

    /**
     * @Route("/orders/{id}/call", name="callOrder")
     * @ParamConverter("forder", class="AppBundle:Forder")
     */
    public function callAction(Request $request, $forder)
    {
        $this->get('app.OrderService')->changeOrderState($forder, 3);
        return $this->render('default/content/order/show.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'forder' => $forder
        ]);
    }

    /**
     * @Route("/orders/{id}/deliver", name="deliverOrder")
     * @ParamConverter("forder", class="AppBundle:Forder")
     */
    public function deliverAction(Request $request, $forder)
    {
        $price = $request->get('price');
        $this->get('app.OrderService')->changeOrderState($forder, 4, $price);
        return $this->render('default/content/order/show.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'forder' => $forder
        ]);
    }

    /**
     * @Route("/orders/{id}/complete", name="completeOrder")
     * @ParamConverter("forder", class="AppBundle:Forder")
     */
    public function completeAction(Request $request, $forder)
    {
        $this->get('app.OrderService')->changeOrderState($forder, 5);
        return $this->render('default/content/order/show.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'forder' => $forder
        ]);
    }
}
