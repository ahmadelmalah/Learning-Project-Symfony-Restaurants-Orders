<?php
namespace AppBundle\Services;

use Doctrine\ORM\EntityManager;

class RestaurantService
{
    protected $em;

    /**
    * Service constructor.
    * @param EntityManager $entityManager
    */
    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    public function create($restaurant){
      $this->em->persist($restaurant);
      $this->em->flush();
      return true;
    }
}
