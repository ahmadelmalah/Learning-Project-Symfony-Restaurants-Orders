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
}
