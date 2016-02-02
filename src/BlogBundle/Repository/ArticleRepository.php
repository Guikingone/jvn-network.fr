<?php

namespace BlogBundle\Repository;

use Doctrine\ORM\EntityRepository;
/**
 * ArticleRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ArticleRepository extends \Doctrine\ORM\EntityRepository
{

  public function getArticle($categorie)
  {
    return $this->createQueryBuilder('ab')
                ->leftJoin('ab.image', 'i')
                  ->addSelect('i')
                ->where('ab.categorie = :categorie')
                  ->setParameter('categorie', $categorie)
                ->orderBy('ab.datePublication', 'DESC')
                ->getQuery()
                ->getResult();
  }

  public function getUpdateArticle($id)
  {
      return $this->createQueryBuilder('a')
                  ->leftJoin('a.image', 'i')
                    ->addSelect('i')
                  ->where('a.id = :id')
                    ->setParameter('id', $id)
                  ->getQuery()
                  ->getResult();
  }
}
