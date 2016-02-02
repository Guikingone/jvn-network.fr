<?php

namespace BlogBundle\Controller;

use BlogBundle\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use BlogBundle\Form\Type\ArticleType;
use BlogBundle\Form\Type\CommentaireType;
use BlogBundle\Entity\Image;

class MembreController extends Controller {

  public function indexAction(Request $request)
  {
    /** On récupère les articles via le service Blog, ce dernier récupère les articles via la catégorie et
    renvoit le tout via la fonction index */
    $article = $this->get('corebundle.blog')->index('MEMBRE');
    return $this->render('BlogBundle:Membre:index.html.twig', array(
      'article' => $article
    ));
  }

  public function viewAction(Article $article, Request $request, $id)
  {
    /* On va chercher l'article en fonction de son ID, si article inexistant, alors
    on retourne un message d'erreur 404, sinon, on affiche l'article puis les commentaires */
    $view = $this->getDoctrine()->getManager();
    $vue = $view->getRepository('BlogBundle:Article')->find($article);
    /** On récupère les commentaires liés à l'article via l'article et on y joint les
    commentaires afin de pouvoir faire article->getCommentaires(), une fois effectuée,
    on affichera tout ceci via une boucle for dans la vue */
    $comm = $view->getRepository('BlogBundle:Commentaire')->findBy(array('article' => $vue));
    $commentaire = new Commentaire();
    $commentaire->setdateCreation(new \Datetime);
    $commentaire->setArticle($article);
    $user = $this->getUser();
    $commentaire->setAuteur($user);
    $formCommentaire = $this->createForm(CommentaireType::class, $commentaire);
    $formCommentaire->handleRequest($request);
    if($formCommentaire->isValid()){
      $em = $this->getDoctrine()->getManager();
      $em->persist($commentaire);
      $em->flush();
    }
    return $this->render('BlogBundle:Membre:view.html.twig', array(
      'article' => $view,
      'commentaire' => $view,
      'form' => $view->createView()
    ));
  }

  public function adminAction(Request $request)
  {
    /* On récupère les articles via le service Blog */
    $article = $this->get('corebundle.blog')->index('MEMBRE');
    return $this->render('BlogBundle:Membre:admin.html.twig', array(
      'article' => $article
    ));
  }

  public function addAction(Request $request)
  {
    /* On créer un nouvel article, on définit la date en fonction du jour
    afin de faciliter le travail de l'auteur, si besoin, il pourra la modifier via le formulaire, on ajoute aussi
    la catégorie afin de forcer l'affichage automatique */
    $article = new Article();
    $article->setDatePublication(new \Datetime);
    $article->setCategorie('MEMBRE');
    $article->setImage(new Image);
    $user = $this->getUser();
    $article->setAuteur($user);

    /* On appelle le formulaire depuis le namespace Form, on définit l'objet qui l'appelle puis on fait le lien
    requête <-> formulaire */
    $formbuilder = $this->createForm(ArticleType::class, $article);
    $formbuilder->handleRequest($request);

    /* On vérifie que les données sont valides, on appelle BigBrother qui écoutera les articles postés,
    on les persist, on enregistre le tout et on renvoit un message
    flash afin de valider l'enregistrement de l'article */
    if($formbuilder->isValid()){
      $em = $this->getDoctrine()->getManager();
      $em->persist($article);
      $em->flush();
      $request->getSession()->getFlashBag()->add('success', "Article enregistré");
      return $this->redirectToRoute('membre_admin');
    }
    return $this->render('BlogBundle:Membre:add.html.twig', array(
      'form' => $formbuilder->createView()
    ));
  }

  public function updateAction(Request $request, $id)
  {
    $update = $this->get('corebundle.blog')->update($request, $id);
    return $this->render('BlogBundle:Membre:update.html.twig', array(
      'form' => $update->createView(),
      'article' => $update
    ));
  }

  public function deleteAction(Request $request, $id)
  {
    /* On récupère le service Blog et l'action delete afin de supprimer les articles, puis
    on renvoit un message flash et on redirige vers la page d'administration */
    $em = $this->get('coreBundle.blog');
    $em->delete($id);
    $request->getSession()->getFlashBag()->add('success', "L'article avec l'id " . $id . " a été supprimé");
    return $this->redirectToRoute('membre_admin');
  }
}
