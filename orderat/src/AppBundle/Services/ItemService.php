<?php
namespace AppBundle\Services;

use Doctrine\ORM\EntityManager;
use AppBundle\Entity\State;
use AppBundle\Entity\Item;
use AppBundle\Entity\Forder;
use Symfony\Component\Config\Definition\Exception\Exception;


class ItemService
{
    protected $entityManager;
    protected $user;

    /**
    * Helper constructor.
    * @param EntityManager $entityManager
    * @param StateRepository $stateRepository
    */
     public function __construct(EntityManager $entityManager, $iUser)
     {
         $this->entityManager = $entityManager;
         $this->user = $iUser;
     }

    /*
    * Inserts items inside an order
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

    private function save(Item $item){
        $this->entityManager->persist($item);
        $this->entityManager->flush();
    }
}
