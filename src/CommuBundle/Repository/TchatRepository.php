<?php

namespace CommuBundle\Repository;

/**
 * TchatRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TchatRepository extends \Doctrine\ORM\EntityRepository
{
  public function getTchat()
  {
    return $this->createQueryBuilder('t')
                ->getQuery()
                ->getResult();
  }
}
