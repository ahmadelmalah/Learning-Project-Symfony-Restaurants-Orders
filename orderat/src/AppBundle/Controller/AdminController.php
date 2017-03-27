<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Entity\Restaurant;

use AppBundle\Form\RestaurantType;


class AdminController extends Controller
{
    /**
     * @Route("/admin", name="admin")
     */
    public function indexAction(Request $request)
    {
        $count_users = $this->get('app.AdminService')->getTotal('users');
        $count_restaurants = $this->get('app.AdminService')->getTotal('restaurants');
        $count_forders = $this->get('app.AdminService')->getTotal('forders');
        $count_items = $this->get('app.AdminService')->getTotal('items');

        return $this->render('admin/content/main.html.twig', [
          'count_users' => $count_users,
          'count_restaurants' => $count_restaurants,
          'count_forders' => $count_forders,
          'count_items' => $count_items,
          'admin_name' => $this->getUser()->getUsername()
        ] );
    }

    /**
     * @Route("/admin/new-restaurant", name="admin-new-restaurant")
     */
    public function newRestaurantAction(Request $request)
    {
          $restaurant = new Restaurant();
          $form = $this->createForm(RestaurantType::class, $restaurant);
          $form->handleRequest($request);

          if ($form->isSubmitted() && $form->isValid()) {

                  $em = $this->getDoctrine()->getManager();
                  $em->persist($restaurant);
                  $em->flush();

                  return $this->redirectToRoute('admin');
              }

          return $this->render('admin/content/new-restaurant.html.twig', [
              'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
              'form' => $form->createView(),
              'admin_name' => $this->getUser()->getUsername()
          ]);
    }
}
