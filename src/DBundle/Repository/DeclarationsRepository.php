<?php

namespace DBundle\Repository;

/**
 * DocumentRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class DeclarationsRepository extends \Doctrine\ORM\EntityRepository
{
  public function DeclarationsTruncate()
  {
    $con = $this->getEntityManager()->getConnection();
    $sql = "SET FOREIGN_KEY_CHECKS = 0;
        TRUNCATE TABLE Declarations;
        ";
    $stmt = $con->prepare($sql);
    return $stmt->execute();
  }
}
