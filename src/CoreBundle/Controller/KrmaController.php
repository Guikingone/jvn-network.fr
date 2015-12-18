<?php

namespace CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class KrmaController extends Controller{

  public function indexAction(){
    return $this->render('CoreBundle::krma.html.twig');
  }
}
