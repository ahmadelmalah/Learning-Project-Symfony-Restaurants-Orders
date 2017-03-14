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

    public function ready($forderID)
    {
        $em = $this->em;

        $forder = $em->getRepository('AppBundle:Forder')->find($forderID);
        $state = $em->getRepository('AppBundle:State')->find(2); //ready

        $forder->setState($state);

        $em->persist($forder);
        $em->flush();
    }

    public function call($forderID)
    {
        $em = $this->em;

        $forder = $em->getRepository('AppBundle:Forder')->find($forderID);
        $state = $em->getRepository('AppBundle:State')->find(3); //waiting

        $forder->setState($state);
        $forder->setCalledAt(new \DateTime("now"));

        $em->persist($forder);
        $em->flush();
    }

    public function deliver($forderID)
    {
        $em = $this->em;

        $forder = $em->getRepository('AppBundle:Forder')->find($forderID);
        $state = $em->getRepository('AppBundle:State')->find(4); //delivered

        $forder->setState($state);
        $forder->setCalledAt(new \DateTime("now"));

        $em->persist($forder);
        $em->flush();
    }

    public function complete($forderID)
    {
        $em = $this->em;

        $forder = $em->getRepository('AppBundle:Forder')->find($forderID);
        $state = $em->getRepository('AppBundle:State')->find(5); //complete

        $forder->setState($state);
        $forder->setCalledAt(new \DateTime("now"));

        $em->persist($forder);
        $em->flush();
    }

    public function getOrders(){
        $em = $this->em;
        $forders = $em->getRepository('AppBundle:Forder')->findAll();
        return $forders;
    }
}
