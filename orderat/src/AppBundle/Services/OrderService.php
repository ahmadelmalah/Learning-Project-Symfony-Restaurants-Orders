<?php
namespace AppBundle\Services;

use Doctrine\ORM\EntityManager;

class OrderService
{
    protected $em;
    protected $user;
    protected $knp_paginator;

    const NUM_PAGES = 5;

    /**
    * Helper constructor.
    * @param EntityManager $entityManager
    * @param StateRepository $stateRepository
    */
     public function __construct(EntityManager $entityManager, $iUser, $knp_paginator_service)
     {
         $this->em = $entityManager;
         $this->user = $iUser;
         $this->knp_paginator = $knp_paginator_service;
     }

     /*
     * Creates an order
     */
    public function create($forder)
    {
        $em = $this->em;
        $user = $this->user;

        //Initial Values
        $state = $em->getRepository('AppBundle:state')->find(1); //active

        $forder->setState($state);
        $forder->setUser($user);
        $forder->setCreatedAt(new \DateTime("now"));

        $em->persist($forder);
        $em->flush();
    }

    public function changeOrderState($forder, $nextState, $price = null){
      $em = $this->em;
      $user = $this->user;

      //Validation
      if($user->getID() != $forder->getUser()->getID()){
        return;
      }
      if($nextState == 2){ //if it changed to ready
          if ( $forder->getItems()->count()  == 0 ){
            return false;
          }
      }

      $state = $em->getRepository('AppBundle:State')->find($nextState);
      $forder->setState($state);

      if($nextState == 3){ //if it changed to called
          $forder->setCalledAt(new \DateTime("now"));
      }elseif($nextState == 4){ //if it changed to delivered
          $forder->setDeliveredAt(new \DateTime("now"));
          $forder->setPrice($price);
      }elseif($nextState == 5){ //if it changed to completed
          $forder->setCompletedAt(new \DateTime("now"));
      }

      $em->persist($forder);
      $em->flush();
      return true;
    }

    /*
    * Gets some orders according to some filters
    * Here we use state as a filter
    */
    public function getOrders($section, $start = 1, $urlFilter = null){
        $em = $this->em;

        $queryFilter = $this->getQueryFilterFromUrlFilter($section, $urlFilter);

        $forders = $em->getRepository('AppBundle:Forder')->findBy($queryFilter, array('id' => 'DESC'));
        //Pagination Process
        $pagination = $this->knp_paginator->paginate(
            $forders,
            $start,
             self::NUM_PAGES
        );

        return $pagination;
    }

    static function getQueryFilterFromUrlFilter($section, $urlFilter = null){
        $queryFilter = array();

        //Section Filtration
        if($section == 'active' || $section == 'apiActiveOrders'){
          $queryFilter['state'] = array(1, 2, 3);
        }elseif($section == 'archive' || $section == 'apiArchiveOrders'){
          $queryFilter['state'] = array(4,5);
        }

        //Additional Filtrations from URL
        if(isset($urlFilter['restaurant']) && $urlFilter['restaurant']){
          $queryFilter['restaurant'] = $urlFilter['restaurant'];
        }
        if(isset($urlFilter['state']) && $urlFilter['state']){
          $queryFilter['state'] = $urlFilter['state'];
        }
        if(isset($urlFilter['myorders']) && $urlFilter['myorders'] == 1){
          $queryFilter['user'] = $this->user->getID();
        }

        return $queryFilter;
    }

    static function forTest(){
      return 'hey';
    }
}
