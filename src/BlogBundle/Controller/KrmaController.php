<?php

namespace BlogBundle\Controller;

use BlogBundle\Entity\Commentaire;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use BlogBundle\Form\Type\ArticleType;
use BlogBundle\Form\Type\CommentaireType;
use BlogBundle\Entity\Article;
use BlogBundle\Entity\Image;

class KrmaController extends Controller{

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/krma", name="krma")
     */
    public function indexAction()
    {
      /** On récupère les articles via le service Blog, ce dernier récupère les articles via la catégorie et
      renvoit le tout via la fonction index */
      $article = $this->get('corebundle.blog')->index('KRMA');
      return $this->render('Blog/Krma/index.html.twig', array(
        'article' => $article
      ));
    }

    /**
     * @param Article $article
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewAction(Article $article, Request $request)
    {
      /* On va chercher l'article en fonction de son ID, si article inexistant, alors
      on retourne un message d'erreur 404, sinon, on affiche l'article puis les commentaires */
      $view = $this->getDoctrine()->getManager();
      $vue = $view->getRepository('BlogBundle:Article')->find($article);
      $comm = $view->getRepository('BlogBundle:Commentaire')->findBy(array('article' => $vue));
      /** On récupère les commentaires liés à l'article via l'article et on y joint les
      commentaires afin de pouvoir faire article->getCommentaires(), une fois effectuée,
      on affichera tout ceci via une boucle for dans la vue */
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
      return $this->render('Blog/Krma/view.html.twig', array(
        'article' => $vue,
        'commentaire' => $commentaire,
        'form' => $formCommentaire->createView()
      ));
    }

    public function adminAction()
    {
      /* On récupère les articles par catégories afin de les afficher via une boucle for dans le back office du blog,
      au besoin, on paginera le tout afin de fluidifier le résultat */
      $article = $this->get('corebundle.blog')->index('KRMA');
      return $this->render('Blog/Krma/admin.html.twig', array(
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
      $article->setCategorie('KRMA');
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
        return $this->redirectToRoute('krma_admin');
      }
      return $this->render('Blog/Krma/add.html.twig', array(
        'form' =>$formbuilder->createView()
      ));
    }

    public function updateAction(Request $request, $id)
    {
      /* On récupère l'entité via l'ID, si l'article n'existe pas, on renvoit un message d'erreur,
      on ouvre le formulaire, on valide, on affiche un message d'info afin
      de valider l'opération et on redirige vers la page d'administration */
      $update = $this->getDoctrine()->getManager()->getRepository('BlogBundle:Article')->find($id);
      if(null === $update){
        throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
      }
      $form = $this->createForm(ArticleType::class, $update);
      $form->handleRequest($request);

      /* Ici, on se contente de vérifier que tout est valide, on ne persise pas car Doctrine connaît l'entité,
      une fois que tout est terminé, on affiche un message de succés et on redirige vers l'article en question */
      if($form->isValid()){
        $update = $this->getDoctrine()->getManager()->flush();
        $request->getSession()->getFlashBag()->add('success', "L'annonce" . $id . "a bien été modifiée");
        return $this->redirectToRoute('krma_admin');
      }
      return $this->render('Blog/Krma/update.html.twig', array(
        'form' => $form->createView(),
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
