<?php
namespace CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BlogController extends Controller{

    public function indexAction(){
      return $this->render('CoreBundle::blog.html.twig');
    }
}
