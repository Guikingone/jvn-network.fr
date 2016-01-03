<?php

namespace BlogBundle\Controller;

use BlogBundle\Entity\Article;
use BlogBundle\Entity\Commentaires;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MembreController extends Controller {

  public function indexAction()
  {
    return $this->render('BlogBundle:Membre:index.html.twig');
  }

  public function viewAction()
  {

  }

  public function adminAction()
  {
    return $this->render('BlogBundle::admin_membre.html.twig');
  }

  public function addAction()
  {

  }

  public function updateAction()
  {

  }

  public function deleteAction()
  {

  }
}
