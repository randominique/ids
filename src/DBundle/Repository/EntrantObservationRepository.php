<?php

namespace DBundle\Repository;

/**
 * EntrantObservationRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EntrantObservationRepository extends \Doctrine\ORM\EntityRepository
{
    public function getByUser($user)
    {
        $status = "En cours";
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $queryBuilder->select('entrantObservation')
                    ->from(EntrantObservation::class, 'entrantObservation')
                    ->andWhere('entrantObservation.user = :user')
                    ->setParameter('user', $user)
                    ->andWhere('entrantObservation.status != :status')
                    ->setParameter('status', $status);
        $query = $queryBuilder->getQuery();
        return $query->getResult();
    }

}
