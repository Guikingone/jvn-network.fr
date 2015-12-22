<?php

namespace BlogBundle\Controller;

use BlogBundle\Entity\Article;
use BlogBundle\Entity\Commentaires;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class BlogController extends Controller
{
    public function indexAction(){
      // On demande à Doctrine de récupérer les articles puis on les affiche via la vue et une boucle
      $em = $this->getDoctrine()->getManager()->getRepository('BlogBundle:Article');
      $article = $em->findAll();
      return $this->render('BlogBundle::index.html.twig', array(
        'articles' => $article
      ));
    }
}
