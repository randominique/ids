<?php

namespace DBundle\Repository;

/**
 * EntrantObservationRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TacheObservationRepository extends \Doctrine\ORM\EntityRepository
{
    public function uptdateObs($id)
    {
        $conn= $this->getEntityManager()
                    ->getConnection();
                    //$sql="SELECT * FROM `Tache` ";
        // $sql = "UPDATE `TacheObservation` SET `status` = 'Traité' WHERE courrier_id = ".$id." ";
        $sql = "UPDATE `TacheObservation` SET `status` = 'Traité' WHERE `id` = ".$id." ";
        $stmt = $conn->prepare($sql);
        return $stmt->execute();
    }

    public function uptdateObsferme($id)
    {
        $conn= $this->getEntityManager()
                    ->getConnection();
                    //$sql="SELECT * FROM `Tache` ";
        $sql = "UPDATE `TacheObservation` SET `status` = 'Fermé' WHERE courrier_id = ".$id." ";
        $stmt = $conn->prepare($sql);
        return $stmt->execute();
    }
}
