<?php

namespace WE\ReportBundle\Entity\Repository;

/**
 * UsuarioRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UsuarioRepository extends \Doctrine\ORM\EntityRepository {

    public function findByRoleQuery($role) {
        return $this->createQueryBuilder('u')
                        ->where('u.roles LIKE :roles')
                        ->andHaving('u.enabled = true')
                        ->setParameter('roles', '%"' . $role . '"%');
    }

    public function findByRole($role, $data) {
        $qb = $this->findByRoleQuery($role);

        if (strpos($role, 'KAM')) {
            $qb->leftJoin('u.region', 'r')
                    ->andWhere('r.id IN (:regiones)')
                    ->setParameter('regiones', $data);
        } elseif (strpos($role, 'USER_CUENTA')) {
            $qb->andWhere('u.id IN (:users)')
                    ->setParameter('users', $data);
        } else {
            $qb->leftJoin('u.cdcs', 'c')
                    ->andWhere('c.id IN (:cdcs)')
                    ->setParameter('cdcs', $data);
        }

        return $qb->getQuery()->getResult();
    }

    public function getProductoresList($agencia) {
        $role = 'ROLE_USER_PRODUCTOR';
        $qb = $this->createQueryBuilder('u')
            ->where('u.roles LIKE :roles')
            ->andWhere('u.agencia = :agencia')
            ->setParameter('agencia', $agencia)
            ->setParameter('roles', '%"' . $role . '"%');

        return $qb->getQuery()->getResult();
    }

    public function getSupervisoresList($agencia) {
        $role = 'ROLE_USER_SUPERVISOR';
        $qb = $this->createQueryBuilder('u')
            ->where('u.roles LIKE :roles')
            ->andWhere('u.agencia = :agencia')
            ->setParameter('agencia', $agencia)
            ->setParameter('roles', '%"' . $role . '"%');

        return $qb->getQuery()->getResult();
    }

}