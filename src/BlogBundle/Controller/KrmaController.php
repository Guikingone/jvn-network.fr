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
      /* On va chercher l'article en fonction de son ID, si article inexistant, alors
      on retourne un message d'erreur 404, sinon, on affiche l'article puis les commentaires liés */

      $view = $this->getDoctrine()->getManager();
      $vue = $view
          ->getRepository('BlogBundle:Article')
          ->find($article);

      /** On récupère les commentaires liés à l'article via l'article et on y joint les
      commentaires afin de pouvoir faire article->getCommentaires(), une fois effectuée,
      on affichera tout ceci via une boucle for dans la vue */

      $comm = $view
        ->getRepository('BlogBundle:Commentaire')
        ->findBy(array('article' => $vue));

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

      return $this->render('BlogBundle:Krma:view.html.twig', array(
        'article' => $vue,
        'commentaire' => $comm,
        'form' => $formCommentaire->createView()
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
      /* On récupère l'entité via l'ID, si l'article n'existe pas, on renvoit un message d'erreur,
      on ouvre le formulaire, on valide, on affiche un message d'info afin
      de valider l'opération et on redirige vers la page d'administration */

      $um = $this->getDoctrine()->getManager()->getRepository('BlogBundle:Article')->find($id);
      if (null === $um) {
        throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
      }

      $form = $this->createForm(ArticleType::class, $um);
      $form->handleRequest($request);

      /* Ici, on se contente de vérifier que tout est valide, on ne persise pas car Doctrine connaît l'entité,
      une fois que tout est terminé, on affiche un message de succés et on redirige vers l'article en question */
      if($form->isValid())
      {
        $um = $this->getDoctrine()->getManager()->flush();
        $request->getSession()->getFlashBag()->add('success', "L'annonce" . $id . "a bien été modifiée");
        return $this->redirectToRoute('krma_admin');
      }
      return $this->render('BlogBundle:Krma:update.html.twig', array(
        'form' => $form->createView(),
        'article' => $um
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
