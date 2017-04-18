<?php
namespace AppBundle\Services;

use Doctrine\ORM\EntityManager;
use AppBundle\Entity\State;
use AppBundle\Entity\Item;
use AppBundle\Entity\Forder;


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
    public function create(Item $item, Forder $forder)
    {
      $em = $this->em;
      $user = $this->user;

      if ($forder->getState()->getID() == State::ACTIVE){
        $item->setForder($forder);
        $item->setUser($user);

        $this->save($item);
        return true;
      }else{
        return false;
      }
    }

    private function save(Item $item){
        $this->em->persist($item);
        $this->em->flush();
    }
}
