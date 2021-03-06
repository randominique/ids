<?php

namespace DBundle\Repository;

/**
 * MissionsRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MissionsRepository extends \Doctrine\ORM\EntityRepository
{

  public function updatevall($id)
  {
      $conn = $this->getEntityManager()
          ->getConnection();
      $sql = "UPDATE `Missions` SET `status` = 'Traité' WHERE id = " . $id . " ";
      $stmt = $conn->prepare($sql);

      return $stmt->execute();
  }

  public function updatevallferme($id)
  {
      $conn = $this->getEntityManager()
          ->getConnection();
      $sql = "UPDATE `Missions` SET `status` = 'Clôturé' WHERE id = " . $id . " ";
      $stmt = $conn->prepare($sql);

      return $stmt->execute();
  }

  public function getByNif($thisNif = null)
  {
      $queryBuilder = $this->getEntityManager()->createQueryBuilder();
      $queryBuilder->select('mission')
          ->from(Missions::class, 'mission');

      $queryBuilder->andWhere('mission.nif LIKE :thisNif')
          ->setParameter('thisNif', '%' . $thisNif . '%');

      $query = $queryBuilder->getQuery();

      return $query->getResult();
  }

  public function getByRsoc($thisRsoc = null)
  {
      $queryBuilder = $this->getEntityManager()->createQueryBuilder();
      $queryBuilder->select('mission')
          ->from(Missions::class, 'mission');

      $queryBuilder->andWhere('mission.raisonSocial LIKE :thisNif')
          ->setParameter('thisNif', '%' . $thisRsoc . '%');

      $query = $queryBuilder->getQuery();

      return $query->getResult();
  }

}
