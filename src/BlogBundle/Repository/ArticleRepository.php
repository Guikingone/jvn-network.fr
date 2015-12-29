<?php

namespace BlogBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
/**
 * ArticleRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ArticleRepository extends \Doctrine\ORM\EntityRepository
{
  public function getArticle($page, $nbPerPpage)
  {
    /* On joint les images à chaque article et on trie le tout par order descendant */
    $query = $this->createQueryBuilder('a')
                  ->leftJoin('a.image', 'i')
                    ->addSelect('i')
                  ->orderBy('a.datePublication', 'DESC')
                  ->getQuery();

    /* On définit la pagination, on commence par l'article d'où partira la pagination,
    puis on définit le nombre maximum d'article par page */
    $query
      ->setFirstResult(($page-1) * $nbPerPpage)
      ->setMaxResults($nbPerPpage);

    return new Paginator($query, true);
  }
}
