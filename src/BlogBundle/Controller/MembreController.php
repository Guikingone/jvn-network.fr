<?php

namespace BlogBundle\Controller;

use BlogBundle\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use BlogBundle\Form\Type\ArticleType;
use BlogBundle\Form\Type\CommentaireType;

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

  public function viewAction(Article $article, Request $request)
  {
    $view = $this->get('corebundle.blog')->view($request, $id);
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
    $article = $this->get('corebundle.blog')->add($request, 'MEMBRE');
    return $this->render('BlogBundle:Membre:add.html.twig', array(
      'form' => $article->createView()
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
