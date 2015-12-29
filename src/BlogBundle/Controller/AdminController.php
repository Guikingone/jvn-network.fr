<?php
namespace BlogBundle\Controller;

use BlogBundle\Entity\Article;
// Si on veut ajouter une image, use BlogBundle\Entity\Commentaires;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use BlogBundle\Form\ArticleType;

class AdminController extends Controller
{

  public function adminAction(Request $request)
  {
    /* On créer un nouvel article,on définit la date en fonction du jour
    afin de faciliter le travail de l'auteur, si besoin, il pourra la modifier via le formulaire */
    $art = new Article();
    $art->setDatePublication(new \Datetime);

    /* On appelle le formulaire depuis le namespace Form, on définit l'objet qui l'appelle puis on fait le lien
    requête <-> formulaire */
    $formbuilder = $this->createForm(ArticleType::class, $art);
    $formbuilder->handleRequest($request);
    /* On vérifie que les données sont valides, on les persist, on enregistre le tout et on renvoit un message
    flash afin de valider l'enregistrement de l'article */
        if($formbuilder->isValid()){
          $em = $this->getDoctrine()->getManager();
          $em->persist($art);
          $em->flush();
          $request->getSession()->getFlashBag()->add('success', "Article enregistré");
        }
    return $this->render('BlogBundle::admin.html.twig', array(
      'form' => $formbuilder->createView(),
    ));
  }

  public function deleteAction(Request $request)
  {
    // On récupère l'entité, puis on effectue un ->remove() afin de supprimer l'article.
  }

  public function updateAction(Request $request)
  {
    /* On récupère l'entité puis on créer le formulaire de modification */
    }
}
