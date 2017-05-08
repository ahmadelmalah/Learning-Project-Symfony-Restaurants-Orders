<?php
namespace AppBundle\Utils\Traits;

trait ServiceDataPersistenceTrait {
    function save($forder) {
      $this->entityManager->persist($forder);
      $this->entityManager->flush();
    }
}
