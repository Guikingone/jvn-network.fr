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
    public function indexAction($page){
      /** On récupére les articles via le repository Article et la fonction getArticle puis
      on calcule le nombre d'article par page afin qu'il match avec $nbrPerPage, si la page
      est plus grande que le nombres de pages, on affiche une erreur sinon, on retourne la vue
      avec les variables transmises */
      if ($page < 1) {
          throw $this->createNotFoundException("La page ".$page." n'existe pas.");
      }

      $nbrPerPage = 10;

      $listArticle = $this->getDoctrine()
                          ->getManager()
                          ->getRepository('BlogBundle:Article')
                          ->getArticle($page, $nbrPerPage);
      $nbPages = ceil(count($listArticle)/$nbrPerPage);

      if($page > $nbPages){
        throw $this->CreateNotFoundException("La page . $page . n'existe pas");
      }

      return $this->render('BlogBundle::index.html.twig', array(
        'article' => $listArticle,
        'nbPages' => $nbPages,
        'page' => $page
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
