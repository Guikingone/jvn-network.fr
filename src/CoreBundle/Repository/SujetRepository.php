<?php

namespace CoreBundle\Repository;

/**
 * SujetRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SujetRepository extends \Doctrine\ORM\EntityRepository
{
  public function getSujet($categorie)
  {
    return $this->createQueryBuilder('s')
                ->where('s.category = :category')
                  ->setParameter('category', $categorie)
                ->orderBy('s.dateCreation', 'DESC')
                ->getQuery()
                ->getResult();
  }
}
