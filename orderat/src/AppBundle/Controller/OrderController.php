<?php

namespace AppBundle\Controller;
//Framework
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\{Request, Response, Session\Session};
//External Bundles
use FOS\RestBundle\Controller\FOSRestController;
//Entities
use AppBundle\Entity\{Restaurant, Forder, Item};
//Forms
use AppBundle\Form\{RestaurantType, ForderType, ItemType, FilterType};
//Utilities
use AppBundle\Utils\PaginatorUtil;

class OrderController extends FOSRestController
{
    /**
     * @Route("/active", name="active")
     * @Route("/archive", name="archive")
     */
    public function showOrdersAction(Request $request)
    {
        $form = $this->createForm(FilterType::class);
        $form->handleRequest($request);

        $filterArray = array();
        if( $request->get('filter') ){
          $filterArray = $request->get('filter');
        }
        //return new Response($request->get('filter'));
        if($request->get('_route') =='active' || $request->get('_route') == 'archive'){
          return $this->render('default/content/active.html.twig', [
              'form_filter' => $form->createView(),
              'section' => $request->get('_route'),
              //'page' => $request->query->getInt('page', 1),
              'params' => $filterArray//http_build_query($_GET)

          ]);
        }
    }

    /**
     * @Route("/ajax/orders/active", name="ajax-active")
     * @Route("/ajax/orders/archive", name="ajax-archive")

     * @Route("/api/orders/active", name="apiActiveOrders")
     * @Route("/api/orders/archive", name="apiArchiveOrders")

     */
    public function showOrdersAjaxAction(Request $request)
    {
        $form = $this->createForm(FilterType::class);
        $form->handleRequest($request);

        $page = $request->query->getInt('page', 1);
        $orders_per_page = $this->get('app.OrderService')->getServiceConstant('ORDERS_PER_PAGE');

        $forders = $this->get('app.OrderService')->getOrders(
          $request->get('_route'), //$section parm: current route
          $page,
          $request->query->get('filter') //$filter parm: an array collects all filter daa
        );

        $count = $this->get('app.OrderService')->getOrdersCount(
          $request->get('_route'), //$section parm: current route
          $page,
          $request->query->get('filter') //$filter parm: an array collects all filter daa
        );

        $paginator = new PaginatorUtil($count, $orders_per_page, $page);

        //return new Response($num_of_items);
        //return new Response($request->get('_route'));
        if($request->get('_route') =='ajax-active' || $request->get('_route') == 'ajax-archive'){
          return $this->render('default/content/orders.ajax.html.twig', [
              'forders' => $forders,
              'count' => $count,
              'paginator' => $paginator,
              'form_filter' => $form->createView(),
          ]);
        }

        //API Logic Goes Here
        $view = $this->get('api')->getView($forders, array('state', 'user', 'restaurant'));
        return $this->handleView($view);
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
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/orders/{id}/add", name="addItems")
     * @ParamConverter("forder", class="AppBundle:Forder")
     */
    public function addAction(Request $request, Forder $forder)
    {
        if (!$this->get('app.OrderService')->isActive($forder) == true){
            return $this->redirectToRoute('homepage');
        }
        $item = new Item();
        $form = $this->createForm(ItemType::class, $item);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try{
              $this->get('app.ItemService')->create($item, $forder);
              $this->get('session')->getFlashBag()->add('ItemAdded', "Your Item {$item->getName()} was added successfuly");
            }catch(Exception $e){
              $this->get('session')->getFlashBag()->add('ItemNotAdded', $e->getMessage());
            }


            return $this->redirectToRoute("addItems", array('id' => $forder->getID()));
        }

        return $this->render('default/content/order/add.html.twig', [
            'form' => $form->createView(),
            'href_backToOrder' => "/orders/{$forder->getID()}/show"
        ]);
    }

    /**
     * @Route("/orders/{id}/show", name="showOrder")
     * @ParamConverter("forder", class="AppBundle:Forder")
     */
    public function showAction(Request $request, Forder $forder)
    {
        return $this->render('default/content/order/show.html.twig', [
            'forder' => $forder
        ]);
    }

    /**
     * @Route("/orders/{id}/ready", name="readyOrder")
     * @ParamConverter("forder", class="AppBundle:Forder")
     */
    public function readyAction(Request $request, Forder $forder)
    {
        try{
          $this->get('app.OrderService')->makeReady($forder);
        }catch(Exception $e){
          $this->get('session')->getFlashBag()->add('errors', $e->getMessage());
        }

        return $this->render('default/content/order/show.html.twig', [
            'forder' => $forder
        ]);
    }

    /**
     * @Route("/orders/{id}/call", name="callOrder")
     * @ParamConverter("forder", class="AppBundle:Forder")
     */
    public function callAction(Request $request, Forder $forder)
    {
        try{
          $this->get('app.OrderService')->makeWaiting($forder);
        }catch(Exception $e){
          $this->get('session')->getFlashBag()->add('errors', $e->getMessage());
        }

        return $this->render('default/content/order/show.html.twig', [
            'forder' => $forder
        ]);
    }

    /**
     * @Route("/orders/{id}/deliver", name="deliverOrder")
     * @ParamConverter("forder", class="AppBundle:Forder")
     */
    public function deliverAction(Request $request, Forder $forder)
    {
        try{
          $price = (float) $request->get('price');
          $this->get('app.OrderService')->makeDelivered($forder, $price);
        }catch(Exception $e){
          $this->get('session')->getFlashBag()->add('errors', $e->getMessage());
        }

        return $this->render('default/content/order/show.html.twig', [
            'forder' => $forder
        ]);
    }

    /**
     * @Route("/orders/{id}/complete", name="completeOrder")
     * @ParamConverter("forder", class="AppBundle:Forder")
     */
    public function completeAction(Request $request, Forder $forder)
    {
        try{
          $this->get('app.OrderService')->makeComplete($forder);
        }catch(Exception $e){
          $this->get('session')->getFlashBag()->add('errors', $e->getMessage());
        }

        return $this->render('default/content/order/show.html.twig', [
            'forder' => $forder
        ]);
    }
}
