<?php

/*
 * This file is part of the jvn-network project.
 *
 * (c) Guillaume Loulier <contact@guillaumeloulier.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CoreBundle\Events;

use CoreBundle\Entity\Article;

/**
 * Class NewArticleEvent
 *
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
class NewArticleEvent
{
    const NAME = 'article.added';

    /** @var Article */
    public $article;

    /**
     * NewArticleEvent constructor.
     *
     * @param Article $article
     */
    public function __construct (Article $article)
    {
        $this->article = $article;
    }

    /** @return Article */
    public function getArticle() : Article
    {
        return $this->article;
    }
}