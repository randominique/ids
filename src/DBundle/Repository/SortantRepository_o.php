<?php

namespace DBundle\Repository;

use DBundle\Entity\Sortant;

/**
 * SortantRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SortantRepository extends \Doctrine\ORM\EntityRepository
{
    public function getByNif($thisNif = null)
    {
			$queryBuilder = $this->getEntityManager()->createQueryBuilder();
	        $queryBuilder->select('commnunication')
                     	->from(Sortant::class, 'commnunication');
        
            $queryBuilder->andWhere('commnunication.nif LIKE :thisNif')
                         ->setParameter('thisNif', '%'.$thisNif.'%');
        

        $query = $queryBuilder->getQuery();

        return $query->getResult();
    }
    public function getByRsoc($thisRsoc = null)
    {
			$queryBuilder = $this->getEntityManager()->createQueryBuilder();
	        $queryBuilder->select('commnunication')
                     	->from(Sortant::class, 'commnunication');
        
            $queryBuilder->andWhere('commnunication.raisonSocial LIKE :thisNif')
                         ->setParameter('thisNif', '%'.$thisRsoc.'%');
        

        $query = $queryBuilder->getQuery();

        return $query->getResult();
    }
}
