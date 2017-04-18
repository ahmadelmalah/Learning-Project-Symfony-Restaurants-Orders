<?php
namespace AppBundle\Services;

use Doctrine\ORM\EntityManager;
use AppBundle\Entity\Restaurant;

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

    public function create(Restaurant $restaurant){
      $this->save($restaurant);
      return true;
    }

    private function save(Restaurant $restaurant){
      $this->em->persist($restaurant);
      $this->em->flush();
    }
}
