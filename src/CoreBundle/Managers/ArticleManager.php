<?php

/*
 * This file is part of the jvn-network project.
 *
 * (c) Guillaume Loulier <contact@guillaumeloulier.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CoreBundle\Managers;

use CoreBundle\Entity\Article;
use CoreBundle\Form\Type\ArticleType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMInvalidArgumentException;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;

/**
 * Class ArticleManager
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class ArticleManager
{
    /** @var EntityManager */
    private $doctrine;

    /** @var FormFactory */
    private $form;

    /** @var Session */
    private $session;

    /** @var RequestStack */
    private $request;

    /**
     * ArticleManager constructor.
     *
     * @param EntityManager $doctrine
     * @param FormFactory   $form
     * @param Session       $session
     * @param RequestStack  $request
     */
    public function __construct (
        EntityManager $doctrine,
        FormFactory $form,
        Session $session,
        RequestStack $request
    ) {
        $this->doctrine = $doctrine;
        $this->form = $form;
        $this->session = $session;
        $this->request = $request;
    }

    /**
     * @return array|Article[]
     */
    public function getArticles()
    {
        return $this->doctrine->getRepository(Article::class)
                              ->findAll();
    }

    /**
     * @param string $title
     *
     * @return Article|null|object
     */
    public function getArticleByName(string $title)
    {
        return $this->doctrine->getRepository(Article::class)
                              ->findOneBy([
                                  'titre' => $title
                              ]);
    }

    /**
     * @param $category
     *
     * @return array|Article[]
     */
    public function getArticlesByCategory($category)
    {
        return $this->doctrine->getRepository(Article::class)
                              ->findBy([
                                  'categorie' => $category
                              ]);
    }

    /**
     * @throws InvalidOptionsException
     * @throws ORMInvalidArgumentException
     * @throws OptimisticLockException
     *
     * @return \Symfony\Component\Form\FormView
     */
    public function postNewArticle()
    {
        $article = new Article();
        $form = $this->form->create(ArticleType::class, $article);
        $form->handleRequest($this->request->getCurrentRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            $this->doctrine->persist($article);
            $this->doctrine->flush();
            $this->session->getFlashBag()->add('success', 'Votre article a bien été publié.');
        }

        return $form->createView();
    }
}