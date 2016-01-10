<?php

namespace BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class BlogController extends Controller {

  public function indexAction(Request $request, $id)
  {
    $index = $this->getDoctrine()
                  ->getManager()
                  ->getRepository('BlogBundle:Article');

    $team = $index->getArticleTeam($id);
    $krma = $this->getArticleKrma($id);
    $membre = $this->getArticleMembre($id);

    return $this->render('BlogBundle::home.html.twig');
  }

  public function updateAction(Request $request, $id)
  {
    /* On récupère l'entité via l'ID, si l'article n'existe pas, on renvoit un message d'erreur,
    on ouvre le formulaire, on valide, on affiche un message d'info afin
    de valider l'opération et on redirige vers la page d'administration */
    $um = $this->getDoctrine()
               ->getManager()
               ->getRepository('BlogBundle:Article')
               ->find($id);
    if (null === $um) {
      throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
    }

    $form = $this->createForm(ArticleType::class, $um);
    $form->handleRequest($request);


    /* Ici, on se contente de vérifier que tout est valide, on ne persise pas car Doctrine connaît l'entité,
    une fois que tout est terminé, on affiche un message de succés et on redirige vers l'article en question */

    if($form->isValid())
    {
      $um = $this->getDoctrine()
                 ->getManager()
                 ->flush();
      $request->getSession()->getFlashBag()->add('success', "L'annonce" . $id . "a bien été modifiée");
      return $this->redirectToRoute('team_admin');
    }
    return $this->render('BlogBundle::update.html.twig', array(
      'form' => $form->createView(),
      'article' => $um
    ));
  }
}
