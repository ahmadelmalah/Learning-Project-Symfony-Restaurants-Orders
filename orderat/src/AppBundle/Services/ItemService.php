<?php
namespace AppBundle\Services;

use Doctrine\ORM\EntityManager;

class ItemService
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

    public function create($item, $forderID)
    {
      $em = $this->em;
      $user = $this->user;

      //Initial Values
      $forder = $em->getRepository('AppBundle:Forder')->find($forderID); //active

      $item->setForder($forder);
      $item->setUser($user);

      $em->persist($item);
      $em->flush();
    }

    public function getItems(){
        // $em = $this->em;
        // $forders = $em->getRepository('AppBundle:Forder')->findAll();
        // return $forders;
    }
}
