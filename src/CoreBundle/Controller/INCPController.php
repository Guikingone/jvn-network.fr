<?php
namespace CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class INCPController extends Controller{

  public function indexAction(){
    return $this->render('CoreBundle::inscription.html.twig');
  }
}
