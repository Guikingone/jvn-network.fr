<?php

namespace UserBundle\Repository;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends \Doctrine\ORM\EntityRepository
{
  public function getUser()
  {
    return $this->createQueryBuilder('u')
                ->getQuery()
                ->getResult();
  }

  public function deleteUser($id)
  {
    return $this->createQueryBuilder('u')
                ->where('u.id = :id')
                  ->setParameter('id', $id)
                ->delete()
                ->getQuery()
                ->getResult();
  }
}
