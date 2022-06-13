<?php

namespace DBundle\Repository;

/**
 * ExercicesVerifiesRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ExercicesVerifiesRepository extends \Doctrine\ORM\EntityRepository
{
  public function DossiersSQVFTruncate()
  {
    $conn = $this->getEntityManager()->getConnection();
    $sql = "SET FOREIGN_KEY_CHECKS = 0;
        TRUNCATE TABLE DossiersSQVF;
        ";
    $stmt = $conn->prepare($sql);
    return $stmt->execute();
  }

  public function evTruncate()
  {
    $conn = $this->getEntityManager()->getConnection();
    $sql = "SET FOREIGN_KEY_CHECKS = 0;
        TRUNCATE TABLE ExercicesVerifies;
        ";
    $stmt = $conn->prepare($sql);
    return $stmt->execute();
  }

  public function vecTruncate()
  {
    $conn = $this->getEntityManager()->getConnection();
    $sql = "SET FOREIGN_KEY_CHECKS = 0;
        TRUNCATE TABLE VerificationsEnCours;
        ";
    $stmt = $conn->prepare($sql);
    return $stmt->execute();
  }

  public function allTruncate()
  {
    $conn = $this->getEntityManager()->getConnection();
    $sql = "SET FOREIGN_KEY_CHECKS = 0;
        TRUNCATE TABLE sqvfDossiers;
        TRUNCATE TABLE DossiersSQVF;
        TRUNCATE TABLE ExercicesVerifies;
        TRUNCATE TABLE VerificationsEnCours;
        // UPDATE `Entrant` SET `status` = 'Nouveau', `gestionnaire_id` = NULL ,`updated_at`= NULL, `delegation_date`= NULL, `traitement_date`= NULL, `attribution`= NULL, `service_id` = '2', `priority` = 'Normal' WHERE 'status' <> 'Nouveau' ;
        // UPDATE `users` SET `nbrecourrier` = '0', `nbretache` = '0' WHERE users.enabled = '1' ;
        ";
    $stmt = $conn->prepare($sql);
    return $stmt->execute();
  }

}