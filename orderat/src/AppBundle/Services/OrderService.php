<?php
namespace AppBundle\Services;

use Doctrine\ORM\EntityManager;
use AppBundle\Entity\State;
use AppBundle\Entity\Forder;
use Symfony\Component\Config\Definition\Exception\Exception;
use AppBundle\Utils\QueryFilter;
use AppBundle\Utils\Traits\ServiceDataPersistenceTrait;

include('Constants/OrderServiceConstants.php');

class OrderService
{
    protected $entityManager;
    protected $user;
    protected $knp_paginator;

    use ServiceDataPersistenceTrait;

    /**
    * Order Service constructor.
    *
    * @param EntityManager $entityManager
    * @param $user represents the current user
    */
     public function __construct(EntityManager $entityManager, $user)
     {
         $this->entityManager = $entityManager;
         $this->user = $user;
     }

     /**
     * Creates a new order
     *
     * @param Forder $forder
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

    /**
    * Changes the order state to ready
    *
    * @param Forder $forder
    */
    public function makeReady(Forder $forder){
        $this->changeOrderState($forder, State::READY);
        $this->save($forder);
    }

    /**
    * Changes the order state to waiting
    *
    * @param Forder $forder
    */
    public function makeWaiting(Forder $forder){
        $this->changeOrderState($forder, State::WAITING);

        $forder->setCalledAt(new \DateTime("now"));

        $this->save($forder);
    }

    /**
    * Changes the order state to delivered
    *
    * @param Forder $forder
    * @param float $price
    *
    * @throws exception if price is not number
    * @throws exception if price out of range
    */
    public function makeDelivered(Forder $forder, float $price){
        $this->changeOrderState($forder, State::DELIVERED);

        if(is_numeric($price) == false){
            throw new Exception("Price should be a number");
        }
        if($price < 0 || $price > 100000){
            throw new Exception("Please enter a number between 0 and 100,000");
        }
        $forder->setDeliveredAt(new \DateTime("now"));
        $forder->setPrice($price);

        $this->save($forder);
    }

    /**
    * Changes the order state to complete
    *
    * @param Forder $forder
    */
    public function makeComplete(Forder $forder){
        $this->changeOrderState($forder, State::COMPLETE);
        $forder->setCompletedAt(new \DateTime("now"));
        $this->save($forder);
    }

    /**
    * Changes the order state to a specif state
    * This is a genral function called by all other functions that change the state
    *
    * @param Forder $forder
    * @param int $StateID
    *
    * @throws exception if user is not the order creator
    * @throws exception if there is no items
    */
    public function changeOrderState(Forder $forder, int $StateID){
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
    }

    /**
    * checks if Order is active
    *
    * @param Forder $forder
    *
    * @return bool
    */
    public function isActive(Forder $forder){
        return $forder->getState()->getID() == State::ACTIVE;
    }

    /**
    * gets the count of orders with some crietria
    *
    * @param string $section
    * @param int $start = 1
    * @param array $urlFilter = null
    *
    * @return int
    */
    public function getOrdersCount(string $section, int $start = 1, array $urlFilter = null){
        $filter = $this->getQueryFilterArray($section, $urlFilter, $this->user->getID())->getSQLFilter();
        return $this->entityManager->getRepository('AppBundle:Forder')->getCount($filter);
    }

    /**
    * gets the orders with some crietria
    *
    * @param string $section
    * @param int $start = 1
    * @param array $urlFilter = null
    *
    * @return group of orders
    */
    public function getOrders($section, $start = 1, $urlFilter = null){
      $forders = $this->entityManager->getRepository('AppBundle:Forder')->findBy(
        $this->getQueryFilterArray($section, $urlFilter, $this->user->getID())->getArray() ,
        $this->getQuerySortArray(),
        ORDERS_PER_PAGE,
        ($start-1) * ORDERS_PER_PAGE
      );

      return $forders;
    }

    /**
    * gets doctrine filter from a url filter
    *
    * @param string $section
    * @param array $urlFilter = null
    * @param int $userID = null
    *
    * @return queryFilter Object
    */
    static function getQueryFilterArray(string $section, array $urlFilter = null, int $userID = null){

        $queryFilter = new QueryFilter;
        if( in_array($section, CURRENT_ORDERS_ROUTES_ARRAY) ){
          $queryFilter->addFilter('state', CURRENT_ORDERS_STATES_ARRAY);
        }elseif( in_array($section, HISTORY_ORDERS_ROUTES_ARRAY) ){
            $queryFilter->addFilter('state', HISTORY_ORDERS_STATES_ARRAY);
        }

        if(isset($urlFilter['myorders']) && intval($urlFilter['myorders']) === 1)
          $queryFilter->addFilter('user', $userID);

        if(isset($urlFilter['restaurant']))
          $queryFilter->addFilter('restaurant', $urlFilter['restaurant']);

        if(isset($urlFilter['state']))
          $queryFilter->addFilter('state', $urlFilter['state']);


        return $queryFilter;
    }

    /**
    * Gets the sorting array
    *
    * @return array to be used by doctrine
    */
    private function getQuerySortArray(){
        return array('id' => 'DESC');
    }

    /**
    * Returns any service constant to any external consumer
    *
    * @param string $constant
    *
    * @return the value of the corresponding constant
    */
    public function getServiceConstant(string $constant){
      return constant($constant);
    }

}
