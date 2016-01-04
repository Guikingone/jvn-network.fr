<?php

namespace BlogBundle\Controller;

use BlogBundle\Entity\Article;
use BlogBundle\Entity\Commentaires;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Doctrine\ORM\EntityManagerInterface;
use BlogBundle\Form\Type\ArticleType;
use BlogBundle\Form\Type\ArticleEditType;
use BlogBundle\Form\Type\CommentaireType;

class MembreController extends Controller {

  public function indexAction(Request $request)
  {
    /** On récupère les articles via le repository Article et la fonction getArticleMembre,  puis on retourne le tout
    dans la vue via une boucle for afin d'afficher les articles */
    $article = $this->getDoctrine()
                    ->getManager()
                    ->getRepository('BlogBundle:Article')
                    ->getArticleMembre();

    return $this->render('BlogBundle:Membre:index.html.twig', array(
      'article' => $article
    ));
  }

  public function viewAction()
  {

  }

  public function adminAction(Request $request)
  {
    /* On récupère les articles via les catégories, on affiche le tout via la boucle for définie dans la vue,
    au besoin, on paginera le tout pour fluidifier le résultat */
    $article = $this->getDoctrine()
                    ->getManager()
                    ->getRepository('BlogBundle:Article')
                    ->getArticleMembre();

    return $this->render('BlogBundle:Membre:admin.html.twig', array(
      'article' => $article
    ));
  }

  public function addAction(Request $request)
  {
    /* On créer un nouvel article, on définit la date en fonction du jour
    afin de faciliter le travail de l'auteur, si besoin, il pourra la modifier via le formulaire, on ajoute aussi
    la catégorie afin de forcer l'affichage automatique */
    $art = new Article();
    $art->setDatePublication(new \Datetime);
    $art->setCategorie('MEMBRE');

    /* On appelle le formulaire depuis le namespace Form, on définit l'objet qui l'appelle puis on fait le lien
    requête <-> formulaire */
    $formbuilder = $this->createForm(ArticleType::class, $art);
    $formbuilder->handleRequest($request);

    /* On vérifie que les données sont valides, on appelle BigBrother qui écoutera les articles postés,
    on les persist, on enregistre le tout et on renvoit un message
    flash afin de valider l'enregistrement de l'article */
        if($formbuilder->isValid()){
          $em = $this->getDoctrine()->getManager();
          $em->persist($art);
          $em->flush();
          $request->getSession()->getFlashBag()->add('success', "Article enregistré");
        }

    return $this->render('BlogBundle:Membre:add.html.twig', array(
      'form' => $formbuilder->createView()
    ));
  }

  public function updateAction()
  {

  }

  public function deleteAction(Request $request, $id)
  {
      /* On récupère l'entité via son ID, on fait appel à removeArticle qui effectue un ->delete()
      en fonction de l'ID, une fois effectué, on affiche un message d'info afin de valider la procédure
      et on redirige vers l'espace d'administration */

      $em = $this->getDoctrine()
                 ->getManager()
                 ->getRepository('BlogBundle:Article')
                 ->removeArticle($id);

      $request->getSession()->getFlashBag()
              ->add('success', "L'article avec l'id " . $id . " a été supprimé");

      return $this->redirectToRoute('membre_admin');
  }
}
