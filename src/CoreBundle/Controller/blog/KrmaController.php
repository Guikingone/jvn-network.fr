<?php

namespace CoreBundle\Controller\Blog;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use CoreBundle\Form\Type\ArticleType;
use CoreBundle\Entity\Article;
use CoreBundle\Entity\Commentaire;

/**
 * @Route("/krma")
 */
class KrmaController extends Controller{

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/", name="krma")
     * @Template("Blog\Krma\index.html.twig")
     */
    public function indexAction()
    {
      $article = $this->get('core.back')->index('KRMA');
      return array('article' => $article);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/admin", name="krma_admin")
     * @Template("Blog\Krma\admin.html.twig")
     */
    public function adminAction(Request $request)
    {
        $article = $this->get('core.back')->index('KRMA');
        $form = $this->get('core.back')->addArticle($request, 'KRMA');
        return array('article' => $article,'form' => $form->createView());
    }

    /**
     * @param Article $article
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/article/{id}/{slug}", name="krma_view", requirements={"id": "\d+"})
     * @Template("Blog\Krma\view.html.twig")
     */
    public function viewAction(Article $article, Request $request)
    {
        $form = $this->get('core.back')->viewArticle($request, $article);
        $commentaire = $this->getDoctrine()
                            ->getManager()
                            ->getRepository('CoreBundle:Commentaire')
                            ->findBy(array('article' => $article));
        return array('article' => $article, 'commentaire' => $commentaire, 'form' => $form->createView());
    }
    
    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/admin/update/{id}", name="krma_update", requirements={"id": "\d+"})
     * @Template("Blog\krma\update.html.twig")
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
          return array('form' => $form->createView(), 'article' => $update);
        }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/admin/delete/{id}", name="krma_delete", requirements={"id": "\d+"})
     */
    public function deleteAction(Request $request, $id)
    {
      $this->get('core.back')->deleteArticle($id);
      return $this->redirectToRoute('krma_admin');
    }
}
