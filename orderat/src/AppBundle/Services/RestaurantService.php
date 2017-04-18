<?php
namespace AppBundle\Services;

use Doctrine\ORM\EntityManager;
use AppBundle\Entity\Restaurant;

class RestaurantService
{
    protected $entityManager;

    /**
    * Service constructor.
    * @param EntityManager $entityManager
    */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function create(Restaurant $restaurant){
      $this->save($restaurant);
    }

    private function save(Restaurant $restaurant){
      $this->entityManager->persist($restaurant);
      $this->entityManager->flush();
    }
}
