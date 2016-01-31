<?php

namespace BlogBundle\Controller;

use BlogBundle\Entity\Article;
use BlogBundle\Entity\Commentaire;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use BlogBundle\Form\Type\ArticleType;
use BlogBundle\Form\Type\CommentaireType;

class KrmaController extends Controller{

  public function indexAction()
  {
    /** On récupère les articles via le service Blog, ce dernier récupère les articles via la catégorie et
    renvoit le tout via la fonction index */
    $article = $this->get('corebundle.blog')->index('KRMA');
    return $this->render('BlogBundle:Krma:index.html.twig', array(
      'article' => $article
    ));
  }

    public function viewAction(Article $article, Request $request)
    {
      $view = $this->get('corebundle.blog')->view($request, $id);
      return $this->render('BlogBundle:Krma:view.html.twig', array(
        'article' => $view,
        'commentaire' => $view,
        'form' => $view->createView()
      ));
    }

    public function adminAction()
    {
      /* On récupère les articles par catégories afin de les afficher via une boucle for dans le back office du blog,
      au besoin, on paginera le tout afin de fluidifier le résultat */
      $article = $this->get('corebundle.blog')->index('KRMA');
      return $this->render('BlogBundle:Krma:admin.html.twig', array(
        'article' => $article
      ));

    }

    public function addAction(Request $request)
    {
      $article = $this->get('corebundle.blog')->add($request, 'KRMA', 'krma_admin');
      return $this->render('BlogBundle:Krma:add.html.twig', array(
        'form' =>$article->createView()
      ));
    }

    public function updateAction(Request $request, $id)
    {
      $update = $this->get('corebundle.blog')->update($request, $id);
      return $this->render('BlogBundle:Krma:update.html.twig', array(
        'form' => $update->createView(),
        'article' => $update
      ));
    }

    public function deleteAction(Request $request, $id)
    {
      /* On récupère le service Purge afin de supprimer selon la méthode propre aux articles, puis
      on renvoit un message flash et on redirige vers la page d'administration */
      $em = $this->get('corebundle.blog');
      $em->delete($id);
      $request->getSession()->getFlashBag()->add('success', "L'article avec l'id " . $id . " a été supprimé");
      return $this->redirectToRoute('krma_admin');
    }
}
