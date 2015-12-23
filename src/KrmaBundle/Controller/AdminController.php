<?php
namespace KrmaBundle\Controller;

use KrmaBundle\Entity\Articles;
use KrmaBundle\Entity\Commentaires;
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
