<?php
namespace AppBundle\Utils;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Response;


class Helper
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

    public function Save($obj)
    {
        $em = $this->em;
        $em->persist($obj);
        $em->flush();
    }
}
