<?php
namespace AppBundle\Services;

use Doctrine\ORM\EntityManager;
use AppBundle\Entity\Restaurant;
use AppBundle\Utils\Traits\ServiceDataPersistenceTrait;

class RestaurantService
{
    protected $entityManager;

    use ServiceDataPersistenceTrait;

    /**
    * Restaurant Service constructor.
    * @param EntityManager $entityManager
    */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
    * Creates a new restaurant
    *
    * @param Restaurant $restaurant
    */
    public function create(Restaurant $restaurant){
      $this->save($restaurant);
    }
}
