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
    /* On récupère l'entité via l'ID, si l'article n'existe pas, on renvoit un message d'erreur,
    on ouvre le formulaire, on valide, on affiche un message d'info afin
    de valider l'opération et on redirige vers la page d'administration */
    $um = $this->getDoctrine()->getManager()->getRepository('BlogBundle:Article')->find($id);
    $um->setUpdatedAt(new \Datetime);
    if (null === $um){
      throw new NotFoundHttpException("L'article d'id " . $id . " n'existe pas ou a été supprimée.");
    }
    $form = $this->createForm(ArticleType::class, $um);
    $form->handleRequest($request);

    /* Ici, on se contente de vérifier que tout est valide, on ne persiste pas car Doctrine connaît l'entité,
    une fois que tout est terminé, on affiche un message de succés et on redirige vers l'article en question */
    if($form->isValid()){
      $um = $this->getDoctrine()->getManager()->flush();
      $request->getSession()->getFlashBag()->add('success', "L'annonce" . $id . "a bien été modifiée");
      return $this->redirectToRoute('back_office');
    }
    return $this->render('CoreBundle:Back_Office:update.html.twig', array(
      'form' => $form->createView(),
      'article' => $um
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
