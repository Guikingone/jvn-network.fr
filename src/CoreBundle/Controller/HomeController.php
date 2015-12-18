<?php
namespace CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller{

    public function indexAction(){
      return $this->render('CoreBundle::home.html.twig');
    }

    public function contactAction(){
      return $this->render('CoreBundle::contact.html.twig');
    }

    public function mentionsAction(){
      return $this->render('CoreBundle::mentions.html.twig');
    }
}
