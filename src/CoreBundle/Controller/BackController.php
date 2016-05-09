<?php
namespace CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use BlogBundle\Form\Type\ArticleType;

use BlogBundle\Entity\Article;
use BlogBundle\Entity\Commentaire;
use BlogBundle\Entity\Image;

class BackController extends Controller
{
  /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws NotFoundHttpException
     * @Route("/back_office/update/{id}", requirements={"id": "\d+"}, name="back_office_update")
     */
      public function updateAction(Request $request, $id)
      {
        $update = $this->getDoctrine()->getManager()->getRepository('BlogBundle:Article')->find($id);
        if(null === $update){
          throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
        }
        $form = $this->createForm(ArticleType::class, $update);
        $form->handleRequest($request);
        if($form->isValid()){
          $this->getDoctrine()->getManager()->flush();
          $this->addFlash('success', "L'annonce" . $id . "a bien été modifiée");
          return $this->redirectToRoute('equipe_admin');
        }
        return $this->render('Back_Office/update.html.twig', array(
          'form' => $form->createView(),
          'article' => $update
        ));
      }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/back_office/delete/{id}", requirements={"id": "\d+"}, name="back_office_delete")
     */
      public function deleteAction($id)
      {
        $this->get('core.back')->deleteArticle($id);
        return $this->redirectToRoute('back_office');
      }
}
