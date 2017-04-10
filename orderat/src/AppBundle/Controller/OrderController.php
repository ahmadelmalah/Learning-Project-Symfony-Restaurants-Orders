<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;

use AppBundle\Entity\Restaurant;
use AppBundle\Entity\Forder;
use AppBundle\Entity\Item;

use AppBundle\Form\RestaurantType;
use AppBundle\Form\ForderType;
use AppBundle\Form\ItemType;
use AppBundle\Form\FilterType;

class OrderController extends FOSRestController
{
    /**
     * @Route("/active", name="active")
     * @Route("/archive", name="archive")
     */
    public function showOrdersAction(Request $request)
    {
        $form = $this->createForm(FilterType::class, array('n' => 'name'));
        $form->handleRequest($request);

        $forders = $this->get('app.OrderService')->getOrders(
          $request->get('_route'), //$section parm: current route
          $request->query->getInt('page', 1), //$Page parm
          $request->query->get('filter') //$filter parm: an array collects all filter daa
        );

        //URL to Send
        $ajax_params = http_build_query($_GET);
        //$ajax_params = str_replace('%5B', '[', $ajax_params);
        //$ajax_params = str_replace('%5D', ']', $ajax_params);
        //$ajax_params = str_replace('amp;', '', $ajax_params);

        if($request->get('_route') =='active' || $request->get('_route') == 'archive'){
          return $this->render('default/content/active.html.twig', [
              'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
              'forders' => $forders,
              'form_filter' => $form->createView(),
              'section' => $request->get('_route'),
              //'page' => $request->query->getInt('page', 1),
              'params' => $ajax_params

          ]);
        }

        // //API Logic Goes Here
        //
        // $encoder = new JsonEncoder();
        // $normalizer = new GetSetMethodNormalizer();
        // $serializer = new Serializer(array($normalizer), array($encoder));
        //
        // $normalizer->setIgnoredAttributes(array('state', 'user', 'restaurant'));
        // $normalizer->setCircularReferenceHandler(function ($object) {
        //    return $object->getID();
        // });
        //
        // $ser = $serializer->serialize($forders, 'json');
        //
        // $view = $this->view($ser, 200);
        // $view->setFormat('json');
        // return $this->handleView($view);
    }

    /**
     * @Route("/ajax/orders/active", name="ajax-active")
     * @Route("/ajax/orders/archive", name="ajax-archive")

     * @Route("/api/orders/active", name="apiActiveOrders")
     * @Route("/api/orders/archive", name="apiArchiveOrders")

     */
    public function showOrdersAjaxAction(Request $request)
    {
        $form = $this->createForm(FilterType::class, array('n' => 'name'));
        $form->handleRequest($request);

        $forders = $this->get('app.OrderService')->getOrders(
          $request->get('_route'), //$section parm: current route
          $request->query->getInt('page', 1), //$Page parm
          $request->query->get('filter') //$filter parm: an array collects all filter daa
        );

        //return new Response($request->get('_route'));
        if($request->get('_route') =='ajax-active' || $request->get('_route') == 'ajax-archive'){
          return $this->render('default/content/orders.ajax.html.twig', [
              'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
              'forders' => $forders,
              'form_filter' => $form->createView(),
          ]);
        }

        //API Logic Goes Here

        $encoder = new JsonEncoder();
        $normalizer = new GetSetMethodNormalizer();
        $serializer = new Serializer(array($normalizer), array($encoder));

        $normalizer->setIgnoredAttributes(array('state', 'user', 'restaurant'));
        $normalizer->setCircularReferenceHandler(function ($object) {
           return $object->getID();
        });

        $ser = $serializer->serialize($forders, 'json');

        $view = $this->view($ser, 200);
        $view->setFormat('json');
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
            if ($this->get('app.ItemService')->create($item, $forder) ){
                $this->get('session')->getFlashBag()->add('ItemAdded', "Your Item {$item->getName()} was added successfuly");
            }else{
              $this->get('session')->getFlashBag()->add('ItemNotAdded', "Your Item {$item->getName()} was not add, This order doesn't recieve more items!");
            }


            return $this->redirectToRoute("addItems", array('id' => $forder->getID()));
        }

        return $this->render('default/content/order/add.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'form' => $form->createView(),
            'href_backToOrder' => "/orders/{$forder->getID()}/show"
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
        if ($this->get('app.OrderService')->changeOrderState($forder, 2) == false){
          $this->get('session')->getFlashBag()->add('OrderNotChanged', "Not enough items!");
        }
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
