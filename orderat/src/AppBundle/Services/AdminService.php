<?php
namespace AppBundle\Services;

use Doctrine\ORM\EntityManager;

class AdminService
{
    protected $em;

    /**
    * Helper constructor.
    * @param EntityManager $entityManager
    */
    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    public function getTotal($type){
        switch ($type) {
          case 'users':
            $repoName = 'AppBundle:User';
            break;
          case 'restaurants':
            $repoName = 'AppBundle:Restaurant';
            break;
          case 'forders':
            $repoName = 'AppBundle:Forder';
            break;
          case 'items':
            $repoName = 'AppBundle:Item';
            break;

        }
        return $this->em->getRepository($repoName)->getTotal();
    }
}
