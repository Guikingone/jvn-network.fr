<?php
namespace CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use BlogBundle\Form\Type\ArticleType;
use BlogBundle\Form\Type\CommentaireType;
use BlogBundle\Entity\Article;
use BlogBundle\Entity\Commentaire;
use BlogBundle\Entity\Image;

class BackController extends Controller {

  public function backAction()
  {
    /* On récupère toutes les variables nécessaires au back-office puis on les affiche via la vue correspondante,
    au besoin, on paginera */
    $article = $this->get('corebundle.blog')->index('TEAM');
    $commentaire = $this->getDoctrine()->getManager()->getRepository('BlogBundle:Commentaire')->getCommentaires();
    $sujet = $this->getDoctrine()->getManager()->getRepository('ForumsBundle:Sujet')->getSujet();
    $user = $this->getDoctrine()->getManager()->getRepository('UserBundle:User')->getUser();

    return $this->render('Back_Office/Team.html.twig', array(
      'article' => $article,
      'commentaire' => $commentaire,
      'sujet' => $sujet,
      'user' => $user
    ));
  }

  public function addAction(Request $request)
  {
    /* On créer un nouvel article, on définit la date en fonction du jour
    afin de faciliter le travail de l'auteur, si besoin, il pourra la modifier via le formulaire, on ajoute aussi
    la catégorie afin de forcer l'affichage automatique */
    $article = new Article();
    $article->setDatePublication(new \Datetime);
    $article->setCategorie('TEAM');
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
      return $this->redirectToRoute('back_office');
    }
    return $this->render('Back_Office/add_article.html.twig', array(
      'form' => $formbuilder->createView()
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
      $um = $this->getDoctrine()->getManager()->flush();
      $request->getSession()->getFlashBag()->add('success', "L'annonce" . $id . "a bien été modifiée");
      return $this->redirectToRoute('team_admin');
    }
    return $this->render('Back_Office/update.html.twig', array(
      'form' => $form->createView(),
      'article' => $update
    ));
  }

  public function deleteAction(Request $request, $id)
  {
    /* On récupère le service Blog afin de supprimer les articles via delete, puis
    on renvoit un message flash et on redirige vers la page d'administration */
    $em = $this->get('coreBundle.blog')->delete($id);
    $request->getSession()->getFlashBag()->add('success', "L'article avec l'id " . $id . " a été supprimée.");
    return $this->redirectToRoute('back_office');
  }
}
