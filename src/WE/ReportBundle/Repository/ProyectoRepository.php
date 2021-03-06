<?php

namespace WE\ReportBundle\Repository;

/**
 * ProyectoRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProyectoRepository extends \Doctrine\ORM\EntityRepository {

    public function findTotalesByActivacionesQuery($activaciones) {
         $subquery = $this->createQueryBuilder('sp')
                ->select('sp.id')
                ->leftJoin('sp.activaciones', 'a')
                ->andWhere('a.id IN (:activaciones)')
                ->setParameter('activaciones', $activaciones)
                ->groupBy('sp.id')
                ->getQuery()->getResult();
        
        return $this->createQueryBuilder('p')
                ->select('SUM(p.total_activaciones) as totales')
                ->andWhere('p.id IN (:ids)')
                ->setParameter('ids', $subquery)->getQuery();
    }

    public function findProyectosByUser($user){
        $qb = $this->createQueryBuilder('p')
                ->leftJoin('p.regiones', 'r')
                ->leftJoin('p.cdcs', 'c');

        if ($user->getCdcs()->count()) {
            $qb->andWhere('c.id IN (:cdcs)')
            ->setParameter('cdcs', $this->getIds($user->getCdcs()));
        }

        if ($user->getRegion()) {
            $qb->andWhere('r.id = :region')
            ->setParameter('region', $user->getRegion());
        }

        return $qb->getQuery()->getResult();
    }

    public function findTotalesByActivaciones($activaciones) { 
        return $this->findTotalesByActivacionesQuery($activaciones)->execute();
    }

    protected function getIds($data) {
        $serializer = array();
        foreach ($data as $entry) {
            $serializer[] = $entry->getId();
        }

        return $serializer;
    }

}
