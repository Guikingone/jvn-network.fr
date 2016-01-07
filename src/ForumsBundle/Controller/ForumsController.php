<?php

namespace ForumsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;

use ForumsBundle\Entity\Sujet;
use ForumsBundle\Form\Type\SujetType;

class ForumsController extends Controller
{
    public function indexAction(Request $request)
    {
        return $this->render('ForumsBundle::index.html.twig');
    }

    public function viewAction(Sujet $sujet)
    {
      /* on récupère le sujet selon son ID, on retourne le tout via une boucle for */
      $sujet = $this->getDoctrine()
                   ->getManager()
                   ->getRepository('ForumsBundle:Sujet')
                   ->find($sujet);


      return $this->render('ForumsBundle::view.html.twig', array(
        'sujet' => $sujet
      ));
    }

    public function deleteAction(Request $request, $id)
    {
      /* On récupère le service Purge afin de supprimer le sujet selon son ID, une fois supprimé, on renvoit
      un message flash afin de valider la suppression */
      $delete = $this->get('forumsbundle.forums_purger');
      $delete->purge($id);

      $request->getSession()->getFlashBag()->add('success_forums', 'Sujet supprimé !');

      return $this->redirectToRoute('forums_home');
    }

    public function consolesAction(Request $request)
    {
      $consoles = $this->getDoctrine()
                       ->getManager()
                       ->getRepository('ForumsBundle:Sujet')
                       ->getSujetConsoles();


      $s_Consoles = new Sujet();
      $s_Consoles->setDateCreation(new \Datetime);
      $s_Consoles->setCategory('Consoles');

      /* On appelle le formulaire depuis le namespace Form, on définit l'objet qui l'appelle puis on fait le lien
      requête <-> formulaire */
      $form_consoles = $this->createForm(SujetType::class, $s_Consoles);
      $form_consoles->handleRequest($request);

      /* On vérifie que les données sont valides, on les persist, on enregistre le tout et on renvoit un message
      flash afin de valider l'enregistrement du sujet */
        if($form_consoles->isValid()){
          $em = $this->getDoctrine()->getManager();
          $em->persist($s_Consoles);
          $em->flush();
          $request->getSession()->getFlashBag()->add('success_forums', "Sujet enregistré");
        }

      return $this->render('ForumsBundle:Consoles:index.html.twig', array(
        'consoles' => $consoles,
        'form' => $form_consoles->createView()
      ));
    }

    public function pcAction(Request $request)
    {
      $pc = $this->getDoctrine()
                 ->getManager()
                 ->getRepository('ForumsBundle:Sujet')
                 ->getSujetPC();


      $s_Pc = new Sujet();
      $s_Pc->setDateCreation(new \Datetime);
      $s_Pc->setCategory('Pc');

      /* On appelle le formulaire depuis le namespace Form, on définit l'objet qui l'appelle puis on fait le lien
      requête <-> formulaire */
      $form_pc = $this->createForm(SujetType::class, $s_Pc);
      $form_pc->handleRequest($request);

      /* On vérifie que les données sont valides, on les persist, on enregistre le tout et on renvoit un message
      flash afin de valider l'enregistrement du sujet */
          if($form_pc->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($s_Pc);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success_forums', "Sujet enregistré");
          }


      return $this->render('ForumsBundle:PC:index.html.twig', array(
        'pc' => $pc,
        'form' => $form_pc->createView()
      ));
    }

    public function adminAction(Request $request)
    {
      $admin = $this->getDoctrine()
                    ->getManager()
                    ->getRepository('ForumsBundle:Sujet')
                    ->getSujetAdmin();

      $s_Admin = new Sujet();
      $s_Admin->setDateCreation(new \Datetime);
      $s_Admin->setCategory('Admin');

      /* On appelle le formulaire depuis le namespace Form, on définit l'objet qui l'appelle puis on fait le lien
      requête <-> formulaire */
      $form_admin = $this->createForm(SujetType::class, $s_Admin);
      $form_admin->handleRequest($request);

      /* On vérifie que les données sont valides, on les persist, on enregistre le tout et on renvoit un message
      flash afin de valider l'enregistrement du sujet */
        if($form_admin->isValid()){
          $em = $this->getDoctrine()->getManager();
          $em->persist($s_Admin);
          $em->flush();
          $request->getSession()->getFlashBag()->add('success_forums', "Sujet enregistré");
        }


      return $this->render('ForumsBundle:Admin:index.html.twig', array(
        'admin' => $admin,
        'form' => $form_admin->createView()
      ));
    }
}
