<?php

namespace DBundle\Repository;

use DBundle\Entity\User;
use DoctrineExtensions\Query\Mysql\Month;
/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends \Doctrine\ORM\EntityRepository
{

    public function getByPrenom($thisPrenom = null)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $queryBuilder->select('user')
                    ->from(User::class, 'user')
                    ->andWhere('user.prenom LIKE :thisPrenom')
                        ->setParameter('thisPrenom', '%'.$thisPrenom.'%');
        $query = $queryBuilder->getQuery();
        return $query->getResult();
    }

    public function getByNom($thisNom = null)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $queryBuilder->select('user')
                    ->from(User::class, 'user')
                    ->andWhere('user.nom LIKE :thisPrenom')
                    ->setParameter('thisPrenom', '%'.$thisNom.'%');
        $query = $queryBuilder->getQuery();
        return $query->getResult();
    }
}