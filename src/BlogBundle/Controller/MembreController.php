<?php

namespace BlogBundle\Controller;

use BlogBundle\Entity\Article;
use BlogBundle\Entity\Commentaires;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
// Bien penser à HttpKernel pour afficher l'erreur d'ID

class MembreController extends Controller{

  public function indexAction()
  {

  }

  public function adminAction()
  {
    
  }
}
