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

    public function viewAction($id)
    {
      /* On va chercher l'article en fonction de son ID, si article inexistant, alors
      on retourne un message d'erreur, sinon, on affiche l'article puis les commentaires liés */
      $view = $this->getDoctrine()->getManager();
      $vue = $view
          ->getRepository('BlogBundle:Article')
          ->find($id);

      if(null === $vue){
          throw new NotFoundHttpException("L'article avec l'id" . $id . "n'existe pas ou a été supprimé");
      }

      // On récupère les commentaires liés à l'article via l'article
      $comm = $view
        ->getRepository('BlogBundle:Commentaire')
        ->findBy(array('article' => $vue));

      return $this->render('BlogBundle::view.html.twig', array(
        'article' => $vue,
        'commentaire' => $comm
      ));
    }
}
