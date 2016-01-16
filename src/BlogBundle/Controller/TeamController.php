<?php
namespace BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use BlogBundle\Form\Type\ArticleType;
use BlogBundle\Form\Type\CommentaireType;
use BlogBundle\Entity\Article;
use BlogBundle\Entity\Commentaire;
use BlogBundle\Entity\Image;

class TeamController extends Controller {

  public function indexAction()
  {
    /** On récupère les articles via le service Blog, ce dernier récupère les articles via la catégorie et
    renvoit le tout via la fonction index */
    $article = $this->get('corebundle.blog')->index('TEAM');
    return $this->render('BlogBundle:Team:index.html.twig', array(
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

    return $this->render('BlogBundle:Team:view.html.twig', array(
      'article' => $vue,
      'commentaire' => $comm,
      'form' => $formCommentaire->createView()
    ));
  }

  public function adminAction(Request $request)
  {
    /* On récupère les articles via le service Blog */
    $article = $this->get('corebundle.blog')->index('TEAM');

    /* On récupère tout les membres ainsi que leur attributs, on les affichent pour pouvoir y intervenir en cas de
    besoin, si besoin, on paginera */
    $membre = $this->getDoctrine()
                   ->getManager()
                   ->getRepository('UserBundle:User')
                   ->getUser();

    $commentaire = $this->getDoctrine()
                        ->getManager()
                        ->getRepository('BlogBundle:Commentaire')
                        ->getCommentaires();

    return $this->render('BlogBundle:Team:admin.html.twig', array(
      'article' => $article,
      'membre' => $membre,
      'commentaire' => $commentaire
    ));
  }

  public function addAction(Request $request)
  {
    /* On créer un nouvel article, on définit la date en fonction du jour
    afin de faciliter le travail de l'auteur, si besoin, il pourra la modifier via le formulaire, on ajoute aussi
    la catégorie afin de forcer l'affichage automatique */
    $art = new Article();
    $art->setDatePublication(new \Datetime);
    $art->setCategorie('TEAM');
    $user = $this->getUser();
    $art->setAuteur($user);

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
          return $this->redirectToRoute('team_admin');
        }
    return $this->render('BlogBundle:Team:team_add.html.twig', array(
      'form' =>$formbuilder->createView()
    ));
  }

  public function updateAction(Request $request, $id)
  {
    $update = $this->get('corebundle.blog')->update($id, 'team_admin');
    
    return $this->render('BlogBundle:Team:update.html.twig', array(
      'form' => $form->createView(),
      'article' => $um
    ));
  }

  public function deleteAction(Request $request, $id)
  {
    /* On récupère le service Blog afin de supprimer les articles via delete, puis
    on renvoit un message flash et on redirige vers la page d'administration */
    $em = $this->get('coreBundle.blog');
    $em->delete($id);
    $request->getSession()->getFlashBag()->add('success', "L'article avec l'id " . $id . " a été supprimé");
    return $this->redirectToRoute('team_admin');
  }
}
