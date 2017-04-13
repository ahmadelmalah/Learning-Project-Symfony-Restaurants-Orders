<?php
namespace AppBundle\Utils;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;

class ApiUtil
{
    public function getSerial($object, $ignored){
      $encoder = new JsonEncoder();
      $normalizer = new GetSetMethodNormalizer();
      $serializer = new Serializer(array($normalizer), array($encoder));

      $normalizer->setIgnoredAttributes($ignored);
      $normalizer->setCircularReferenceHandler(function ($obj) {
         return $obj->getID();
      });

      return $serializer->serialize($object, 'json');
    }
}
