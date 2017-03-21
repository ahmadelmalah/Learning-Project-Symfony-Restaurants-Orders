<?php
namespace AppBundle\Services;

use Doctrine\ORM\EntityManager;

class OrderService
{
    protected $em;
    protected $user;
    protected $knp_paginator;

    const NUM_PAGES = 1;

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

    /*
    * Changes an order state to ready
    */
    public function ready($forder)
    {
        $em = $this->em;

        $state = $em->getRepository('AppBundle:State')->find(2); //ready

        $forder->setState($state);

        $em->persist($forder);
        $em->flush();
    }

    /*
    * Changes an order state to called
    */
    public function call($forder)
    {
        $em = $this->em;

        $state = $em->getRepository('AppBundle:State')->find(3); //waiting

        $forder->setState($state);
        $forder->setCalledAt(new \DateTime("now"));

        $em->persist($forder);
        $em->flush();
    }

    /*
    * Changes an order state to delivered
    */
    public function deliver($forder, $price)
    {
        $em = $this->em;

        $state = $em->getRepository('AppBundle:State')->find(4); //delivered

        $forder->setState($state);
        $forder->setPrice($price);
        $forder->setCalledAt(new \DateTime("now"));

        $em->persist($forder);
        $em->flush();
    }

    /*
    * Changes an order state to completed
    */
    public function complete($forder)
    {
        $em = $this->em;

        $state = $em->getRepository('AppBundle:State')->find(5); //complete

        $forder->setState($state);
        $forder->setCalledAt(new \DateTime("now"));

        $em->persist($forder);
        $em->flush();
    }

    /*
    * Gets some orders according to some filters
    * Here we use state as a filter
    */
    public function getOrders($section, $start = 1, $urlFilter = null){
        $em = $this->em;

        $queryFilter = $this->getQueryFilterFromUrlFilter($section, $urlFilter);
        
        $forders = $em->getRepository('AppBundle:Forder')->findBy($queryFilter);

        //Pagination Process
        $pagination = $this->knp_paginator->paginate(
            $forders,
            $start,
             self::NUM_PAGES
        );

        return $pagination;
    }

    public function getQueryFilterFromUrlFilter($section, $urlFilter = null){
        $queryFilter = array();

        //Section Filtration
        if($section == 'active'){
          $queryFilter['state'] = array(1, 2, 3);
        }elseif($section == 'archive'){
          $queryFilter['state'] = array(4,5);
        }

        //Additional Filtrations from URL
        if($urlFilter['restaurant']){
          $queryFilter['restaurant'] = $urlFilter['restaurant'];
        }
        if($urlFilter['state']){
          $queryFilter['state'] = $urlFilter['state'];
        }
        if(isset($urlFilter['myorders']) && $urlFilter['myorders'] == 1){
          $queryFilter['user'] = $this->user->getID();
        }

        return $queryFilter;
    }
}
