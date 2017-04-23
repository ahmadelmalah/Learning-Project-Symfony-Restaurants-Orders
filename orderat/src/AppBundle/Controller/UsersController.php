<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\{Request, Response};


use AppBundle\Entity\State;

class UsersController extends FOSRestController
{
  /**
   * @Route("test", name="test")
   */
  public function testAction(Request $request)
  {
    //$request = $this->get('request');
      $translated = $this->get('translator')->trans('Symfony is great');
      return new Response($translated);
  }
}
