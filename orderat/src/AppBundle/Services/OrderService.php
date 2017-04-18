<?php
namespace AppBundle\Services;

use Doctrine\ORM\EntityManager;
use AppBundle\Entity\State;
use AppBundle\Entity\Forder;
use Symfony\Component\Config\Definition\Exception\Exception;

//Number of items per page
define('ORDERS_PER_PAGE', 5);

class OrderService
{
    protected $entityManager;
    protected $user;
    protected $knp_paginator;
        /**
    * Helper constructor.
    * @param EntityManager $entityManager
    * @param StateRepository $stateRepository
    */
     public function __construct(EntityManager $entityManager, $user, $knp_paginator_service)
     {
         $this->entityManager = $entityManager;
         $this->user = $user;
         $this->knp_paginator = $knp_paginator_service;
     }

     /*
     * Creates an order
     */
    public function create(Forder $forder)
    {
        //Initial Values
        $state = $this->entityManager->getRepository('AppBundle:state')->find(State::ACTIVE);

        $forder->setState($state);
        $forder->setUser($this->user);
        $forder->setCreatedAt(new \DateTime("now"));

        $this->save($forder);
    }

    public function makeReady(Forder $forder){
        $this->changeOrderState($forder, State::READY);
    }

    public function makeWaiting(Forder $forder){
        $forder->setCalledAt(new \DateTime("now"));
        $this->changeOrderState($forder, State::WAITING);
    }

    public function makeDelivered(Forder $forder, $price){
        $forder->setDeliveredAt(new \DateTime("now"));
        $forder->setPrice($price);
        $this->changeOrderState($forder, State::DELIVERED);
    }

    public function makeComplete(Forder $forder){
        $forder->setCompletedAt(new \DateTime("now"));
        $this->changeOrderState($forder, State::COMPLETE);
    }

    public function changeOrderState(Forder $forder, $StateID){
      //Validation: User should be the creator of the restaurant
      if($this->user->getID() != $forder->getUser()->getID()){
        throw new Exception("You're not allowed to update this order!");
      }
      //Validation: Order couldn't be empty of items
      if ($forder->getItems()->count()  == 0){
        throw new Exception("No enough items!");
      }

      $state = $this->entityManager->getRepository('AppBundle:State')->find($StateID);
      $forder->setState($state);

      $this->save($forder);
    }

    /*
    * Gets some orders according to some filters
    * Here we use state as a filter
    */
    public function getOrders($section, $start = 1, $urlFilter = null){
        $queryFilter = $this->getQueryFilterFromUrlFilter($section, $urlFilter, $this->user->getID());

        $forders = $this->entityManager->getRepository('AppBundle:Forder')->findBy($queryFilter, array('id' => 'DESC'));
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
      $this->entityManager->persist($forder);
      $this->entityManager->flush();
    }
}
