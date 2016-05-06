<?php

namespace CoreBundle\Controller\_Blog\Blog;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use CoreBundle\Form\Type\ArticleType;
use CoreBundle\Form\Type\CommentaireType;
use CoreBundle\Entity\Article;
use CoreBundle\Entity\Commentaire;

class KrmaController extends Controller{

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/krma", name="krma")
     */
    public function indexAction()
    {
      $article = $this->get('core.back')->index('KRMA');
      return $this->render('Blog/Krma/index.html.twig', array(
        'article' => $article
      ));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/krma/admin", name="krma_admin")
     */
    public function adminAction(Request $request)
    {
        $article = $this->get('core.back')->index('KRMA');
        $form = $this->get('core.back')->addArticle($request, 'KRMA');
        return $this->render('Blog/Krma/admin.html.twig', array(
            'article' => $article,
            'form' => $form->createView()
        ));
    }

    /**
     * @param Article $article
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/krma/article/{id}/{slug}", name="krma_view", requirements={"id": "\d+"})
     */
    public function viewAction(Article $article, Request $request)
    {
      $view = $this->getDoctrine()->getManager();
      $vue = $view->getRepository('CoreBundle:Article')->find($article);
      $comm = $view->getRepository('CoreBundle:Commentaire')->findBy(array('article' => $vue));
      $commentaire = new Commentaire();
      $commentaire->setdateCreation(new \Datetime);
      $commentaire->setArticle($article);
      $user = $this->getUser();
      $commentaire->setAuteur($user);
      $formCommentaire = $this->createForm(CommentaireType::class, $commentaire);
      $formCommentaire->handleRequest($request);
      if($formCommentaire->isValid()){
        $em = $this->getDoctrine()->getManager();
        $em->persist($commentaire);
        $em->flush();
      }
      return $this->render('Blog/Krma/view.html.twig', array(
        'article' => $vue,
        'commentaire' => $commentaire,
        'form' => $formCommentaire->createView()
      ));
    }


    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/krma/admin/update/{id}", name="krma_update", requirements={"id": "\d+"})
     */
        public function updateAction(Request $request, $id)
        {
          $update = $this->getDoctrine()->getManager()->getRepository('CoreBundle:Article')->find($id);
          if(null === $update){
            throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
          }
          $form = $this->createForm(ArticleType::class, $update);
          $form->handleRequest($request);

          if($form->isValid()){
              $update = $this->getDoctrine()->getManager();
              $update->flush();
              $request->getSession()->getFlashBag()->add('success', "L'annonce" . $id . "a bien été modifiée.");
              return $this->redirectToRoute('krma_admin');
          }
          return $this->render('Blog/Krma/update.html.twig', array(
            'form' => $form->createView(),
            'article' => $update
          ));
        }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/krma/admin/delete/{id}", name="krma_delete", requirements={"id": "\d+"})
     */
    public function deleteAction(Request $request, $id)
    {
      $this->get('core.back')->deleteArticle($id);
      $this->addFlash('success', "L'article avec l'id " . $id . " a été supprimé");
      return $this->redirectToRoute('krma_admin');
    }
}
