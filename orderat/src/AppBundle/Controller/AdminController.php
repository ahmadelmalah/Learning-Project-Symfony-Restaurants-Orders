<?php

namespace AppBundle\Controller;
//Framework
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\{Request, Response};
//Entities
use AppBundle\Entity\{Restaurant};
//Forms
use AppBundle\Form\{RestaurantType};


class AdminController extends Controller
{
    /**
     * @Route("/admin", name="admin")
     */
    public function indexAction(Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        return $this->render('admin/content/main.html.twig', [
          'is_cached' => $this->get('app.AdminService')->isCached(),

          'count_users' => $this->get('app.AdminService')->getTotal('users'),
          'count_restaurants' => $this->get('app.AdminService')->getTotal('restaurants'),
          'count_forders' => $this->get('app.AdminService')->getTotal('forders'),
          'count_items' => $this->get('app.AdminService')->getTotal('items'),

          'admin_name' => $this->getUser()->getUsername()
        ] );
    }

    /**
     * @Route("/admin/new-restaurant", name="admin-new-restaurant")
     */
    public function newRestaurantAction(Request $request)
    {
          $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

          $restaurant = new Restaurant();
          $form = $this->createForm(RestaurantType::class, $restaurant);
          $form->handleRequest($request);
          if ($form->isSubmitted() && $form->isValid()) {
            $this->get('app.RestaurantService')->create($restaurant);
            return $this->redirectToRoute('admin');
          }

          return $this->render('admin/content/new-restaurant.html.twig', [
              'form' => $form->createView(),
              'admin_name' => $this->getUser()->getUsername()
          ]);
    }

    /**
     * @Route("/admin/clear-cache", name="admin-clear-cache")
     */
    public function ClearCacheAction(Request $request)
    {
        $this->get('cache')->deleteAll();
        return $this->redirectToRoute('admin');
    }

}
