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
    $query = $this->createQueryBuilder('a')
      // On joint les images à chaque article
      ->leftJoin('a.image', 'i')
        ->addSelect('i')
      ->orderBy('a.datePublication', 'DESC')
      ->getQuery();

    /* On définit la pagination, on commence part l'article d'où partira la pagination,
    puis on définit le nombre maximum d'article par page */
    $query
      ->setFirstResult(($page-1) * $nbPerPpage)
      ->setMaxResults($nbPerPpage);

    return new Paginator($query, true);
  }
}
