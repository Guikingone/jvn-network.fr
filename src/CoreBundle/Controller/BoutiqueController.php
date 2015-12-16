<?php
namespace CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BoutiqueController extends Controller{

  public function indexAction(){
    return $this->render('CoreBundle::boutique.html.twig');
  }
}
