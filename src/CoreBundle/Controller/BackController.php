<?php
namespace CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class BackController extends Controller {

  public function backAction()
  {
    $article = $this->getDoctrine()->getManager();
    $article->getRepository('BlogBundle:Article')
            ->getArticle();

    $commentaire = $this->getDoctrine()->getManager();
    $commentaire->getRepository('BlogBundle:Commentaire')
                ->getCommentaires();

    $sujet = $this->getDoctrine()->getManager();
    $sujet->getRepository('ForumsBundle:Sujet')
          ->getSujet();

    $user = $this->getDoctrine()->getManager();
    $user->getRepository('UserBundle:User')
         ->getUser();

    return $this->render('CoreBundle:Back_Office:Team.html.twig', array(
      'article' => $article,
      'commentaire' => $commentaire,
      'sujet' => $sujet,
      'user' => $user
    ));
  }
}
