<?php

namespace SIGTASBundle\Repository;

/**
 * ClientsRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ClientsRepository extends \Doctrine\ORM\EntityRepository
{
    

//     public function  ()
//     {
//         return $this->createQueryBuilder('carte')
//             ->andWhere('carte.nif = :val')
//             ->setParameter('val', $contribuable_nif)
//             ->getQuery()
//             ->getArrayResult()
//         ;
//    }

   public function selectquery()
   {
       $conn= $this->getEntityManager()
       ->getConnection();
      //$sql="SELECT * FROM `Tache` ";
       $sql = " SELECT * FROM `Tache` ";
       $stmt = $conn->prepare($sql);
       
       return $stmt->execute();
       
     
   }
   



}
