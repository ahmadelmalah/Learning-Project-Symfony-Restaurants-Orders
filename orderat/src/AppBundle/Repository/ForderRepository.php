<?php

namespace AppBundle\Repository;

use Doctrine\ORM\Query\ResultSetMapping;
use AppBundle\Entity\Forder;

/**
 * ForderRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ForderRepository extends \Doctrine\ORM\EntityRepository
{
    public function getTotal(){
      return $this->getEntityManager()
            ->createQuery(
                'SELECT count(f) FROM AppBundle:Forder f'
            )
            ->getSingleScalarResult();
    }

    public function getCount(string $filter){
      return $this->getEntityManager()
            ->createQuery(
                'SELECT count(f) FROM AppBundle:Forder f WHERE ' . $filter
            )
            ->getSingleScalarResult();
    }
}
