<?php
namespace AppBundle\Services;

use Doctrine\ORM\EntityManager;

class OrderService
{
    protected $em;
    protected $user;

    /**
    * Helper constructor.
    * @param EntityManager $entityManager
    * @param StateRepository $stateRepository
    */
     public function __construct(EntityManager $entityManager, $iUser)
     {
         $this->em = $entityManager;
         $this->user = $iUser;
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
    public function getOrders($section){
        if($section == 'active'){
          $filter = array('state' => array(1, 2, 3));
        }elseif($section == 'archive'){
          $filter = array('state' => array(4, 5));
        }
        $em = $this->em;
        $forders = $em->getRepository('AppBundle:Forder')->findBy($filter);
        return $forders;
    }
}
