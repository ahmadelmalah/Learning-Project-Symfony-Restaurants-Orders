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

    /*
    * Inserts items inside an order
    */
    public function create($item, $forder)
    {
      $em = $this->em;
      $user = $this->user;

      $item->setForder($forder);
      $item->setUser($user);

      $em->persist($item);
      $em->flush();
    }
}
