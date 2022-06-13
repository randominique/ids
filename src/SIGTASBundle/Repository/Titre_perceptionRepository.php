<?php

namespace SIGTASBundle\Repository;

use SIGTASBundle\Entity\Titre_perception;

/**
 * Titre_perceptionRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class Titre_perceptionRepository extends \Doctrine\ORM\EntityRepository
{
  public function selectAll($contribuable_nif)
  {
      return $this->createQueryBuilder('tp')
          ->andWhere('tp.nif = :val')
          ->setParameter('val', $contribuable_nif)
          ->getQuery()
          ->getArrayResult()
      ;
  }

  public function getByNif($thisNif = null)
  {
      $queryBuilder = $this->getEntityManager()->createQueryBuilder();
      $queryBuilder->select('c')
                  ->from(Titre_perception::class, 'c')
                  ->where('c.nif LIKE :thisNif')
                  ->setParameter('thisNif', '%'.$thisNif.'%');
      $query = $queryBuilder->getQuery();
      return $query->getResult();
  }

}
