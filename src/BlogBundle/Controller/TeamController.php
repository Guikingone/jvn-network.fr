<?php
namespace BlogBundle\Controller;

use BlogBundle\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use BlogBundle\Form\Type\ArticleType;
use BlogBundle\Entity\Commentaires;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Doctrine\ORM\EntityManagerInterface;

class TeamController extends Controller
{
  public function adminAction(Request $request)
  {
    $article = $this->getDoctrine()->getManager()
                    ->getRepository('BlogBundle:Article')
                    ->getArticleAll();

    return $this->render('BlogBundle::admin_dev.html.twig', array(
      'article' => $article
    ));
  }

  public function addAction(Request $request)
  {
    /* On créer un nouvel article, on définit la date en fonction du jour
    afin de faciliter le travail de l'auteur, si besoin, il pourra la modifier via le formulaire */
    $art = new Article();
    $art->setDatePublication(new \Datetime);

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
        }
    return $this->render('BlogBundle::team_add.html.twig', array(
      'form' =>$formbuilder->createView()
    ));
  }

  public function deleteAction(Request $request, $id)
  {
    /* On récupère l'entité via son ID, on fait appel à removeArticle qui effectue un ->delete()
    en fonction de l'ID, une fois effectué, on affiche un message d'info afin de valider la procédure
    et on redirige vers l'espace d'administration */
    $em = $this->getDoctrine()->getManager()->getRepository('BlogBundle:Article');
    $em->removeArticle($id);

    $request->getSession()->getFlashBag()
            ->add('success', "L'article avec l'id. $id . a été supprimé");


    return $this->redirectToRoute('team_admin');
  }

  public function updateAction(Request $request)
  {
    return $this->render('BlogBundle::admin_dev.html.twig');
  }

  public function indexAction($page){
    /** On récupére les articles via le repository Article et la fonction getArticle puis
    on calcule le nombre d'article par page afin qu'il match avec $nbrPerPage, si la page
    est introuvable ou plus grande que le nombre d'articles par page, on affiche une erreur sinon,
    on retourne la vue avec les variables transmises */
    if ($page < 1) {
        throw $this->createNotFoundException("La page ".$page." n'existe pas.");
    }

    $nbrPerPage = 10;

    $listArticle = $this->getDoctrine()
                        ->getManager()
                        ->getRepository('BlogBundle:Article')
                        ->getArticle($page, $nbrPerPage);
    $nbPages = ceil(count($listArticle)/$nbrPerPage);

    if($page > $nbPages){
      throw $this->CreateNotFoundException("La page . $page . n'existe pas");
    }

    return $this->render('BlogBundle::index.html.twig', array(
      'article' => $listArticle,
      'nbPages' => $nbPages,
      'page' => $page
    ));
  }

  public function viewAction(Article $article)
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

    return $this->render('BlogBundle::view.html.twig', array(
      'article' => $vue,
      'commentaire' => $comm
    ));
  }
}
