<?php
namespace CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CNXController extends Controller{

  public function indexAction(){
    return $this->render('CoreBundle:connexion.html.twig');
  }
}
