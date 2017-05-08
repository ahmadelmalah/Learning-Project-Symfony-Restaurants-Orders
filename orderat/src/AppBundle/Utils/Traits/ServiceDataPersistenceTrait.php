<?php
namespace AppBundle\Utils\Traits;

trait ServiceDataPersistenceTrait {
    function save(object $forder) {
      $this->entityManager->persist($forder);
      $this->entityManager->flush();
    }
}
