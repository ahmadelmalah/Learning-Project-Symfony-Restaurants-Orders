<?php
namespace AppBundle\Services;

use Doctrine\ORM\EntityManager;
use AppBundle\Entity\State;
use AppBundle\Entity\Item;
use AppBundle\Entity\Forder;
use Symfony\Component\Config\Definition\Exception\Exception;
use AppBundle\Utils\Traits\ServiceDataPersistenceTrait;


class ItemService
{
    protected $entityManager;
    protected $user;

    use ServiceDataPersistenceTrait;

    /**
    * Item Service constructor.
    *
    * @param EntityManager $entityManager
    * @param $user represents the current user
    */
     public function __construct(EntityManager $entityManager, $user)
     {
         $this->entityManager = $entityManager;
         $this->user = $user;
     }

    /**
    * Creates a new item iside an order
    *
    * @param Item $item
    * @param Forder $forder
    *
    * @throws exception if order state is not active
    */
    public function create(Item $item, Forder $forder)
    {
        if ($forder->getState()->getID() != State::ACTIVE){
            throw new Exception("Your Item {$item->getName()} was not add, This order doesn't recieve more items!!");
        }

        $item->setForder($forder);
        $item->setUser($this->user);

        $this->save($item);
    }
}
