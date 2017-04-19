<?php
namespace AppBundle\Utils;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;
use FOS\RestBundle\Controller\FOSRestController;

class APIUtil extends FOSRestController
{
    public function getView($object, $ignored){
      $encoder = new JsonEncoder();
      $normalizer = new GetSetMethodNormalizer();
      $serializer = new Serializer(array($normalizer), array($encoder));

      $normalizer->setIgnoredAttributes($ignored);
      $normalizer->setCircularReferenceHandler(function ($obj) {
         return $obj->getID();
      });

      $serial =  $serializer->serialize($object, 'json');
      $view = $this->view($serial, 200)->setFormat('json');

      return $view;
    }
}
