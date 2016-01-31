<?php
namespace CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use BlogBundle\Form\Type\ArticleType;
use BlogBundle\Entity\Article;

class BackController extends Controller {

  public function backAction()
  {
    /* On récupère toutes les variables nécessaires au back-office puis on les affiche via la vue correspondante,
    au besoin, on paginera */
    $article = $this->get('corebundle.blog')->index('TEAM', 'KRMA', 'MEMBRE');

    $commentaire = $this->getDoctrine()->getManager()->getRepository('BlogBundle:Commentaire')->getCommentaires();

    $sujet = $this->getDoctrine()->getManager()->getRepository('ForumsBundle:Sujet')->getSujet();

    $user = $this->getDoctrine()->getManager()->getRepository('UserBundle:User')->getUser();

    return $this->render('CoreBundle:Back_Office:Team.html.twig', array(
      'article' => $article,
      'commentaire' => $commentaire,
      'sujet' => $sujet,
      'user' => $user
    ));
  }

  public function addAction(Request $request)
  {
    /* Ici, on appelle le service Blog et la fonction add, cette dernière créer un article et renvoie la variable
    $formbuilder afin que le formulaire puisse exister, la fonction add s'occupe de tout le traitement
    et de l'enregistrement de l'article via Doctrine */
    $article = $this->get('corebundle.blog')->add($request, 'TEAM', 'back_office');
    return $this->render('CoreBundle:Back_Office:add_article.html.twig', array(
      'form' => $article->createView()
    ));
  }

  public function updateAction(Request $request, $id)
  {
    $update = $this->get('corebundle.blog')->update($request, $id);
    return $this->render('CoreBundle:Back_Office:update.html.twig', array(
      'form' => $update->createView(),
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
