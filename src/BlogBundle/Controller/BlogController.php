<?php

namespace BlogBundle\Controller;

use BlogBundle\Entity\Article;
use BlogBundle\Entity\Commentaires;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class BlogController extends Controller
{
    public function indexAction(){
      // On récupére les articles puis on les affiche via une boucle
      $em = $this->getDoctrine()->getManager()->getRepository('BlogBundle:Article');
      $article = $em->findAll();
      return $this->render('BlogBundle::index.html.twig', array(
        'article' => $article
      ));
    }

    public function articleAction(){
      return $this->render('BlogBundle::article.html.twig');
    }
}
