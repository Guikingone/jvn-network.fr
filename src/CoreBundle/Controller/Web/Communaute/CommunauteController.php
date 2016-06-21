<?php

namespace CoreBundle\Controller\Web\Communaute;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use CommuBundle\Entity\Tchat;
use CommuBundle\Form\Type\TchatType;

class CommunauteController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/communaute", name="communaute")
     * @Template("Communaute/index.html.twig")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
      $tchat = $this->getDoctrine()->getManager()->getRepository('CommuBundle:Tchat')->getTchat();
      $user = $this->getUser();

      $add = new Tchat();
      $add->setDateCreation(new \Datetime);
      $add->setAuteur($user);

      $form_add = $this->createForm(TchatType::class, $add);
      $form_add->handleRequest($request);
      if($form_add->isValid()){
        $em = $this->getDoctrine()->getManager();
        $em->persist($add);
        $em->flush();
        return $this->redirectToRoute('commu_home');
      }

        return array('tchat' => $tchat, 'form' => $form_add->createView());
    }
}
