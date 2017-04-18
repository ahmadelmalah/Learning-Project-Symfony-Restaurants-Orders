<?php
namespace AppBundle\Services;

use Doctrine\ORM\EntityManager;
use AppBundle\Entity\State;
use AppBundle\Entity\Forder;

//Number of items per page
define('ORDERS_PER_PAGE', 5);

class OrderService
{
    protected $em;
    protected $user;
    protected $knp_paginator;
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
    public function create(Forder $forder)
    {
        $em = $this->em;
        $user = $this->user;

        //Initial Values
        $state = $em->getRepository('AppBundle:state')->find(State::ACTIVE);

        $forder->setState($state);
        $forder->setUser($user);
        $forder->setCreatedAt(new \DateTime("now"));

        $this->save($forder);
    }

    public function changeOrderState($forder, $nextState, $price = null){
      $em = $this->em;
      $user = $this->user;

      //Validation
      if($user->getID() != $forder->getUser()->getID()){
        return;
      }
      if($nextState == State::READY){ //if it changed to ready
          if ( $forder->getItems()->count()  == 0 ){
            return false;
          }
      }

      $state = $em->getRepository('AppBundle:State')->find($nextState);
      $forder->setState($state);

      if($nextState == State::WAITING){ //if it changed to called
          $forder->setCalledAt(new \DateTime("now"));
      }elseif($nextState == State::DELIVERED){ //if it changed to delivered
          $forder->setDeliveredAt(new \DateTime("now"));
          $forder->setPrice($price);
      }elseif($nextState == 5){ //if it changed to completed
          $forder->setCompletedAt(new \DateTime("now"));
      }

      $this->save($forder);
      return true;
    }

    /*
    * Gets some orders according to some filters
    * Here we use state as a filter
    */
    public function getOrders($section, $start = 1, $urlFilter = null){
        $em = $this->em;

        $queryFilter = $this->getQueryFilterFromUrlFilter($section, $urlFilter, $this->user->getID());

        $forders = $em->getRepository('AppBundle:Forder')->findBy($queryFilter, array('id' => 'DESC'));
        //Pagination Process
        $pagination = $this->knp_paginator->paginate(
            $forders,
            $start,
            ORDERS_PER_PAGE
        );

        return $pagination;
    }

    static function getQueryFilterFromUrlFilter($section, $urlFilter = null, $userID = null){
        $queryFilter = array();

        //Section Filtration
        if($section == 'active' || $section == 'ajax-active' || $section == 'apiActiveOrders'){
          $queryFilter['state'] = array(
            State::ACTIVE,
            State::READY,
            State::WAITING
          );
        }elseif($section == 'archive' || $section == 'ajax-archive' || $section == 'apiArchiveOrders'){
          $queryFilter['state'] = array(
            State::DELIVERED,
            State::COMPLETE
          );
        }

        //Additional Filtrations from URL
        if(isset($urlFilter['restaurant']) && $urlFilter['restaurant']){
          $queryFilter['restaurant'] = $urlFilter['restaurant'];
        }
        if(isset($urlFilter['state']) && $urlFilter['state']){
          $queryFilter['state'] = $urlFilter['state'];
        }
        if(isset($urlFilter['myorders']) && $urlFilter['myorders'] == 1){
          $queryFilter['user'] = $userID;
        }

        return $queryFilter;
    }

    private function save(Forder $forder){
      $this->em->persist($forder);
      $this->em->flush();
    }
}
