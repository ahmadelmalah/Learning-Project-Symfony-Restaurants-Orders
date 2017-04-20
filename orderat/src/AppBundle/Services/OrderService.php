<?php
namespace AppBundle\Services;

use Doctrine\ORM\EntityManager;
use AppBundle\Entity\State;
use AppBundle\Entity\Forder;
use Symfony\Component\Config\Definition\Exception\Exception;
use AppBundle\Utils\QueryFilter;

include('Constants/OrderServiceConstants.php');

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
        if(is_numeric($price) == false){
            throw new Exception("Price should be a number");
        }
        if($price < 0 || $price > 100000){
            throw new Exception("Please enter a number between 0 and 100,000");
        }
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
      $forders = $this->entityManager->getRepository('AppBundle:Forder')->findBy(
        $this->getQueryFilterArray($section, $urlFilter, $this->user->getID()),
        $this->getQuerySortArray()
      );

      return $forders;
    }

    public function getOrdersPaginated($section, $start = 1, $urlFilter = null){
      $forders = $this->getOrders($section, $start, $urlFilter);

      $fordersPaginated =  $this->knp_paginator->paginate(
          $forders,
          $start,
          ORDERS_PER_PAGE
      );

      return $fordersPaginated;
    }

    static function getQueryFilterArray($section, $urlFilter = null, $userID = null){
        $queryFilter = new QueryFilter;

        if( in_array($section, CURRENT_ORDERS_ROUTES_ARRAY) ){
          $queryFilter->addFilter('state', CURRENT_ORDERS_STATES_ARRAY);
        }elseif( in_array($section, HISTORY_ORDERS_ROUTES_ARRAY) ){
            $queryFilter->addFilter('state', HISTORY_ORDERS_STATES_ARRAY);
        }
        if(isset($urlFilter['myorders']) && intval($urlFilter['myorders']) === 1){
            $queryFilter->addFilter('user', $userID);
        }
        $queryFilter->addFilter('restaurant', $urlFilter['restaurant']);
        $queryFilter->addFilter('state', $urlFilter['state']);

        return $queryFilter->getArray();
    }

    private function getQuerySortArray(){
        return array('id' => 'DESC');
    }

    private function save(Forder $forder){
      $this->entityManager->persist($forder);
      $this->entityManager->flush();
    }
}
