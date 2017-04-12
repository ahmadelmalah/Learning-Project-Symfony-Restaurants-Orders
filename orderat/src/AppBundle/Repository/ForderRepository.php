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

    // public function getForders(){
    //   $rsm = new ResultSetMapping();
    //
    //   $rsm->addEntityResult('AppBundle\Entity\Forder', 'f');
    //   $rsm->addFieldResult('f', 'id', 'id');
    //   $rsm->addFieldResult('f', 'created_at', 'createdAt');
    //   $rsm->addFieldResult('f', 'called_at', 'calledAt');
    //   $rsm->addFieldResult('f', 'delivered_at', 'deliveredAt');
    //   $rsm->addFieldResult('f', 'completed_at', 'completedAt');
    //   $rsm->addFieldResult('f', 'price', 'price');
    //   $rsm->addJoinedEntityResult('AppBundle\Entity\State', 's' , 'f', 'state');
    //
    //   //$rsm->addFieldResult('f', 'id', 'id');
    //
    //   $result =  $this->getEntityManager()
    //         ->createNativeQuery(
    //             'SELECT * FROM `forder` f
    //             LEFT JOIN state s ON f.state_id = s.id'
    //             FORCE INDEX (IDX_5C6429615D83CC1),
    //
    //             $rsm
    //         )
    //         ->getResult();
    //   dump($result);die();
    //   return $result;
    // }
}
