<?php
namespace CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class BoutiqueController extends Controller{

  public function indexAction(){
    return $this->render('CoreBundle::boutique.html.twig');
  }
}
