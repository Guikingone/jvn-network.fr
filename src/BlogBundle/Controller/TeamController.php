<?php
namespace BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use BlogBundle\Form\Type\ArticleType;
use BlogBundle\Form\Type\CommentaireType;
use BlogBundle\Entity\Article;
use BlogBundle\Entity\Commentaire;

class TeamController extends Controller {

  public function indexAction()
  {
    /** On récupère les articles via le service Blog, ce dernier récupère les articles via la catégorie et
    renvoit le tout via la fonction index */
    $article = $this->get('corebundle.blog')->index('TEAM');
    return $this->render('Blog/Team/index.html.twig', array(
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
    return $this->render('Blog/Team/view.html.twig', array(
      'article' => $vue,
      'commentaire' => $comm,
      'form' => $formCommentaire->createView()
    ));
  }

  public function adminAction(Request $request)
  {
    /* On récupère les articles par catégories afin de les afficher via une boucle for dans le back office du blog,
    au besoin, on paginera le tout afin de fluidifier le résultat */
    $article = $this->get('corebundle.blog')->index('TEAM');

    /* On récupère tout les membres ainsi que leur attributs, on les affichent pour pouvoir y intervenir en cas de
    besoin, si besoin, on paginera */
    $membre = $this->getDoctrine()->getManager()->getRepository('UserBundle:User')->getUser();

    $commentaire = $this->getDoctrine()->getManager()->getRepository('BlogBundle:Commentaire')->getCommentaires();

    return $this->render('Blog/Team/admin.html.twig', array(
      'article' => $article,
      'membre' => $membre,
      'commentaire' => $commentaire
    ));
  }

  public function updateAction(Request $request, $id)
  {
    /* On récupère l'entité via l'ID, si l'article n'existe pas, on renvoit un message d'erreur,
    on ouvre le formulaire, on valide, on affiche un message d'info afin
    de valider l'opération et on redirige vers la page d'administration */

    $um = $this->getDoctrine()->getManager()->getRepository('BlogBundle:Article')->find($id);
    if (null === $um){
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
      return $this->redirectToRoute('team_admin');
    }
    return $this->render('Blog/Team/update.html.twig', array(
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
