<?php

namespace Stk\AdhesionBundle\Repository;

/**
 * MembreRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MembreRepository extends \Doctrine\ORM\EntityRepository
{
    public function findChoralMembre() {
        $query = $this->createQueryBuilder('membre')
            ->where('membre.status like :status')->setParameter('status','c');
        return $query->getQuery()->getResult();
    }
    
    public function findChoralBC() {
        $query = $this->createQueryBuilder('membre')
            ->where('membre.likeAs like :likeAsBureau')->setParameter('likeAsBureau','b')
            ->orWhere('membre.likeAs like :likeAsCommite')->setParameter('likeAsCommite','c');
        return $query->getQuery()->getResult();
    }

    public function getMaxId() {
        $query = $this->createQueryBuilder('membre')
            ->select('MAX(membre.id)');
        return $query->getQuery()->getSingleScalarResult();
    }
    
    public function findByKeyword($keyword) {
        $query = $this->createQueryBuilder('membre')
            ->where('membre.firstName like :firstName')->setParameter('firstName', '%'.$keyword.'%')
            ->orWhere('membre.lastName like :lastName ')->setParameter('lastName', '%'.$keyword.'%');
        return $query->getQuery()->getResult();
    }
}
