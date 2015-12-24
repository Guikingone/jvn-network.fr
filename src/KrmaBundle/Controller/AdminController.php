<?php
namespace KrmaBundle\Controller;

use KrmaBundle\Entity\Articles;
use KrmaBundle\Entity\Commentaires;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AdminController extends Controller{

  public function adminAction(Request $request){
    // On crée un nouvel article
    $art_krma = new Articles();
    // On appelle le formulaire et on le fait hydrater $art
    $form = $this->createFormBuilder($art_krma)
        ->add('titre', TextType::class)
        ->add('content', TextareaType::class)
        ->add('datePublication', DateType::class)
        ->add('save', SubmitType::class, array('label' => 'Créer un article'))
        ->getForm();
        $form->handleRequest($request);
        if($form->isValid()){
          $em = $this->getDoctrine()->getManager();
          $em->persist($art_krma);
          $em->flush();
        }
        // Un fois enregistré, on affiche une annonce flash de succés
        $request->getSession()->getFlashBag()->add('info_krma', "Article enregistré");

    return $this->render('KrmaBundle::admin.html.twig', array(
      'form' => $form->createView()));
  }
}
