<?php

namespace DBundle\Repository;

use DBundle\Entity\Contribuables;

/**
 * ContribuablesRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ContribuablesRepository extends \Doctrine\ORM\EntityRepository
{

  public function getByNif($thisNif = null)
  {
      $queryBuilder = $this->getEntityManager()->createQueryBuilder();
      $queryBuilder->select('c')
                  ->from(Contribuables::class, 'c')
                  ->andWhere('c.nif LIKE :thisNif')
                      ->setParameter('thisNif', '%'.$thisNif.'%');
      $query = $queryBuilder->getQuery();
      return $query->getResult();
  }

  public function getByRsoc($thisRs = null)
  {
      $queryBuilder = $this->getEntityManager()->createQueryBuilder();
      $queryBuilder->select('c')
                  ->from(Contribuables::class, 'c')
                  ->andWhere('c.raisonSociale LIKE :thisRs')
                      ->setParameter('thisRs', '%'.$thisRs.'%');
      $query = $queryBuilder->getQuery();
      return $query->getResult();
  }

}
