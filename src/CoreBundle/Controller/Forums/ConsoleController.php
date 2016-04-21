<?php

namespace CoreBundle\Controller\Forums;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use ForumsBundle\Entity\Sujet;
use ForumsBundle\Form\Type\SujetType;

class ConsoleController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/console", name="forum_console")
     */
      public function indexAction(Request $request)
      {
          $consoles = $this->getDoctrine()->getManager();
          $consoles->getRepository('ForumsBundle:Sujet')->getSujetConsoles();
          return $this->render('Forums/Consoles/index.html.twig', array(
            'consoles' => $consoles
          ));
      }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/console/add", name="console_add")
     */
    public function addAction(Request $request)
    {
      $s_Console = new Sujet();
      $s_Console->setDateCreation(new \Datetime);
      $s_Console->setCategory('Pc');
      $user = $this->getUser();
      $s_Console->setAuteur($user);

      /* On appelle le formulaire depuis le namespace Form, on définit l'objet qui l'appelle puis on fait le lien
      requête <-> formulaire */
      $form_console = $this->createForm(SujetType::class, $s_Console);
      $form_console->handleRequest($request);

      /* On vérifie que les données sont valides, on les persist, on enregistre le tout
      et on redirige vers l'index du forums */
          if($form_console->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($s_Console);
            $em->flush();
            return $this->redirectToRoute('console');
          }

      return $this->render('Forums/Action/add.html.twig', array(
        'console' => $s_Console,
        'form' => $form_console->createView()
      ));
    }
}
