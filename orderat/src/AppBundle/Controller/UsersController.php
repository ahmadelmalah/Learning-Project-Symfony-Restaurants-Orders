<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Config\Definition\Exception\Exception;



use AppBundle\Entity\State;

class UsersController extends FOSRestController
{
  /**
   * @Route("test", name="test")
   */
  public function testAction(Request $request)
  {
    //$request = $this->get('request');
      throw new Exception("Your Item was not add, This order doesn't recieve more items!!");
      $translated = $this->get('translator')->trans('Symfony is great');
      return new Response($translated);
  }
}
