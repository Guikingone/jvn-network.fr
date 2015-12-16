<?php
namespace CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ForumsController extends Controller{

    public function indexAction(){
      return $this->render('CoreBundle::forums.html.twig');
  }
}
