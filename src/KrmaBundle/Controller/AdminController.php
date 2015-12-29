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
    /* On appelle le formulaire et on le fait hydrater $art_krma, on sélectionne les champs à remplir puis
    on les définit avec leur type, on ajoute le bouton de soumission, on obtient la forme du formulaire puis on
    fait la relation formulaire <-> requête, on vérifie que les champs sont valides, on persist les données puis
    on les enregistre dans la BDD */
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
        /* Un fois enregistré, on affiche une annonce flash de succés personnalisé afin que les message
        ne s'affichent pas si ce n'est pas le bon formulaire */
        $request->getSession()->getFlashBag()->add('info_krma', "Article enregistré");

    return $this->render('KrmaBundle::admin.html.twig', array(
      'form' => $form->createView()));
  }
}
