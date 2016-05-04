<?php

namespace CoreBundle\Controller\_Blog;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use BlogBundle\Entity\Article;
use BlogBundle\Form\Type\ArticleType;
use BlogBundle\Entity\Commentaire;
use BlogBundle\Form\Type\CommentaireType;


class BlogController extends Controller
{
    /**
     * @param $categorie
     * @return array
     * Permet d'afficher la liste des articles via la $categorie qui les différencie
     */
    public function index($categorie)
    {
        return $this->getDoctrine()
                    ->getManager()
                    ->getRepository('BlogBundle:Article')
                    ->getArticle($categorie);
    }

    /**
     * @param Request $request
     * @param $categorie
     * @param $route
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function add(Request $request, $categorie, $route)
    {
        $article = new Article();
        $user = $this->getUser();
        $article->setDatePublication(new \Datetime);
        $article->setCategorie($categorie);
        $article->setAuteur($user);

        $formbuilder = $this->createForm(ArticleType::class, $article);
        $formbuilder->handleRequest($request);

        if($formbuilder->isValid()){
            $em = $this->getDoctrine()
                       ->getManager();
            $em->persist($article);
            $em->flush();
            $this->addFlash('success', "Article enregistré");
            return $this->redirectToRoute($route);
        }
    }

    /**
     * @param Request $request
     * @param $id
     * @param $route
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function edit(Request $request, $id, $route)
    {
        $update = $this->getDoctrine()->getManager()->getRepository('BlogBundle:Article')->find($id);
        if(null === $update){
            throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
        }
        $formbuilder = $this->createForm(ArticleType::class, $update);
        $formbuilder->handleRequest($request);
        if($formbuilder->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', "L'annonce" . $id . "a bien été modifiée");
            return $this->redirectToRoute($route);
        }
        return $formbuilder;
    }

    /**
     * @param Request $request
     * @param $id
     */
    public function view(Request $request, $id)
    {
        $view = $this->getDoctrine()->getManager()->getRepository('BlogBundle:Article')->find($id);
        $commentaire = $this->em->getRepository('BlogBundle:Commentaire')->findBy(array('article' => $view));

        $commentaire = new Commentaire();
        $user = $this->getUser();
        $commentaire->setdateCreation(new \Datetime);
        $commentaire->setArticle($article);
        $commentaire->setAuteur($user);
        $formbuilder = $this->createForm(CommentaireType::class, $commentaire);
        $formbuilder->handleRequest($request);
        if($formbuilder->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($commentaire);
            $em->flush();
        }
    }

    /**
     * @param $id
     * Permet de supprimer un article via son $id
     */
    public function delete($id)
    {
        $purge = $this->getDoctrine()->getManager()->getRepository('BlogBundle:Article')->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($purge);
        $em->flush();
    }
}
