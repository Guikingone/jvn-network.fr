<?php
namespace BlogBundle\Controller;

use BlogBundle\Entity\Article;
use BlogBundle\Entity\Commentaires;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends Controller{

  public function adminAction(){
    return $this->render('BlogBundle::admin.html.twig');
  }

  public function addAction(){

  }

  public function updateAction(){

  }

  public function deleteAction(){

  }
}
