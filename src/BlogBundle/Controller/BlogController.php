<?php

namespace BlogBundle\Controller;

use BlogBundle\Entity\Article;
use BlogBundle\Entity\Commentaires;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
// Bien penser à HttpKernel pour afficher l'erreur d'ID

class BlogController extends Controller
{
    public function indexAction(){
      /** On récupére les articles via le repository Article et la fonction myFindAll
      puis on redirige vers la vue correspondate, on pagine pas car on ressort tout les articles */
      $repository = $this->getDoctrine()->getManager()->getRepository('BlogBundle:Article');
      $listArticle = $repository->myFindAll();
      return $this->render('BlogBundle::index.html.twig', array(
        'article' => $listArticle
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
          throw new NotFoundHttpException("L'article avec l'id " . $id . " n'existe pas ou a été supprimée,
          si l'erreur vous semble inadaptée à la situation, veuillez contacter l'administrateur");
      }

      /** On récupère les commentaires liés à l'article via l'article et on y joint les
      commentaires afin de pouvoir faire article->getCommentaires(), une fois effectuée,
      on affichera tout ceci via une boucle for dans la vue */
      $comm = $view
        ->getRepository('BlogBundle:Commentaire')
        ->findBy(array('article' => $vue));

      return $this->render('BlogBundle::view.html.twig', array(
        'article' => $vue,
        'commentaire' => $comm
      ));
    }
}
