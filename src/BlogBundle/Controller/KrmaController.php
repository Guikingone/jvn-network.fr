<?php

namespace BlogBundle\Controller;

use BlogBundle\Entity\Article;
use BlogBundle\Entity\Commentaires;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
// Bien penser à HttpKernel pour afficher l'erreur d'ID

class KrmaController extends Controller
{
    public function indexAction()
    {
      return $this->render('BlogBundle:Krma:index.html.twig');
    }

    public function viewAction(Article $article)
    {

    }

    public function adminAction()
    {

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
