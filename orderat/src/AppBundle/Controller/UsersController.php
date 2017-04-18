<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Entity\State;

class UsersController extends FOSRestController
{
  /**
   * @Route("test", name="test")
   */
  public function testAction(Request $request)
  {
      return new Response(State::ACTIVE);
  }
}
