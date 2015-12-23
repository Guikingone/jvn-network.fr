<?php
namespace BlogBundle\Controller;

use BlogBundle\Entity\Article;
use BlogBundle\Entity\Commentaires;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AdminController extends Controller{

  public function adminAction(Request $request)
  {
    // On crée un nouvel article
    $art = new Article();
    // On appelle le formulaire et on le fait hydrater $art
    $formbuilder = $this->createFormBuilder($art)
        ->add('titre', TextType::class)
        ->add('auteur', TextType::class)
        ->add('contenu', TextareaType::class)
        ->add('datePublication', DateType::class)
        ->add('categorie', TextType::class)
        ->add('save', SubmitType::class, array('label' => 'Créer un article'))
        ->getForm();
        $formbuilder->handleRequest($request);
        if($formbuilder->isValid()){
          $em = $this->getDoctrine()->getManager();
          $em->persist($art);
          $em->flush();
        }
        // Un fois enregistré, on affiche une annonce flash de succés
        $request->getSession()->getFlashBag()->add('success', "Annonce enregistré");

    return $this->render('BlogBundle::admin.html.twig', array(
      'form' => $formbuilder->createView(),
    ));
  }
}
