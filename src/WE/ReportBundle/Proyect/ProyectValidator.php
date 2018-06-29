<?php

namespace WE\ReportBundle\Proyect;

use WE\ReportBundle\Entity\Activacion;
use WE\ReportBundle\Entity\Proyecto;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Doctrine\ORM\EntityManagerInterface;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of StatusGenerator
 *
 * @author dioner911
 */
class ProyectValidator extends Controller {

    private $router;
    private $em;
    private $mailer;
    private $proyect;
    private $valid;
    private $activacion;
    private $messages;

    public function __construct(UrlGeneratorInterface $router, \Swift_Mailer $mailer, EntityManagerInterface $em) {
        $this->router = $router;
        $this->mailer = $mailer;
        $this->em = $em;
        $this->valid = false;
    }

    public function setWarning(Activacion $activacion) {

        $this->proyect = $activacion->getProyecto();
        $this->activacion = $activacion;

        $this->validateFechas();

        //ESTA ES PRIMERO SI NO ES CORRECTO QUE TRUENE POR COMPLETO EL GUARDADO
        //CALCULAR EL TOTAL DE ACTIVACIONES QUE NO ESTEN RECHAZADAS NO ES IMPEDIMIENTO PARA CANCELAR EL GUARDADO
        //VALIDA EL TEMA DE FECHAS/HORARIO Y QUE SEAN PARALELAS VALIDANDO LAS REGLAS EXISTENTES
        //VALIDA REGIONALIZACIÓN
        //+REGION
        //+PLAZA
        //+CDC

        $this->addWarning();
    }

    protected function validateFechas() {
        $this->valid = $this->activacion->getFecha() >= $this->proyect->getFechaInicio() && $this->activacion->getFecha() <= $this->proyect->getFechaFin();

        if (!$this->valid) {
            $this->messages[] = "La fecha del proyecto no esta permitida.";
        }

        $this->validateTotalActivaciones();
    }

    protected function getTotalActivaciones() {
        return $this->proyect->getActivaciones()->filter(function($activacion) {
                    return $activacion->getStatus()->getId() != 3;
                });
    }

    protected function validateTotalActivaciones() {
        if ($this->getTotalActivaciones()->count() > $this->proyect->getTotalActivaciones()) {
            $this->messages[] = "El total de activaciones es mayor a las disponibilidad.";
        }

        $this->validateSimultaneidad();
    }

    protected function validateSimultaneidad() {
        $qb = $this->em->getRepository('ReportBundle:Activacion')->createQueryBuilder('a');

        $results = $qb
                ->leftJoin('a.cdc', 'c')
                ->leftJoin('c.plaza', 'p')
                ->leftJoin('a.proyecto', 'pr')
                ->where('c.plaza = :plaza')
                ->setParameter('plaza', $this->activacion->getCdc()->getPlaza())
                ->andWhere('a.fecha LIKE :fecha')
                ->setParameter('fecha', "%" . $this->activacion->getFecha()->format('Y-m-d') . "%")
                ->andWhere('pr.id = :proyecto')
                ->setParameter('proyecto', $this->proyect->getId())
                ->getQuery()
                ->getResult();
        

        if (count($results) > $this->proyect->getMaximoPlaza()) {
            $this->messages[] = "Hay otra activación en simultaneo en la plaza";
        }

        $this->validateAsignaciones();
    }

    protected function validateAsignaciones() {
        if ($this->proyect->getAsignacionesRegion()->count()) {
            $this->validateAsignacionRegion();
        }
        
        if ($this->proyect->getAsignacionesSquare()->count()) {
            $this->validateAsignacionPlaza();
        }

        if ($this->proyect->getAsignacionesCdc()->count()) {
            $this->validateAsignacionCdc();
        }
    }

    protected function validateAsignacionRegion() {
        $region = $this->activacion->getCdc()->getPlaza()->getRegion();

        $entities = $this->proyect->getAsignacionesRegion()->filter(function($activacion) use ($region) {
            return $activacion->getRegion() == $region;
        });

        $totales = 0;

        foreach ($entities as $entity) {
            $totales = $entity->getTotal();
        }
        
        $activaciones = $this->proyect->getActivaciones()->filter(function($activacion) use ($region) {
            return $activacion->getCdc()->getPlaza()->getRegion() == $region;
        });
        

        if (count($activaciones) > $totales) {
            $this->messages[] = "No hay hay activaciones disponibles para esta región";
        }
    }

    protected function validateAsignacionCdc() {
        $cdc = $this->activacion->getCdc();

        $entities = $this->proyect->getAsignacionesCdc()->filter(function($activacion) use ($cdc) {
            return $activacion->getCdc() == $cdc;
        });

        $totales = 0;

        foreach ($entities as $entity) {
            $totales = $entity->getTotal();
        }

        $activaciones = $this->proyect->getActivaciones()->filter(function($activacion) use ($cdc) {
            return $activacion->getCdc() == $cdc;
        });

        if (count($activaciones) > $totales) {
            $this->messages[] = "No hay hay activaciones disponibles para este Centro de Consumo";
        }
    }

    protected function validateAsignacionPlaza() {
        $plaza = $this->activacion->getCdc()->getPlaza();

        $entities = $this->proyect->getAsignacionesSquare()->filter(function($activacion) use ($plaza) {
            return $activacion->getPlaza() == $plaza;
        });

        $totales = 0;

        foreach ($entities as $entity) {
            $totales = $entity->getTotal();
        }

        $activaciones = $this->proyect->getActivaciones()->filter(function($activacion) use ($plaza) {
            return $activacion->getCdc()->getPlaza() == $plaza;
        });

        if (count($activaciones) > $totales) {
            $this->messages[] = "No hay hay activaciones disponibles para esta plaza";
        }
    }

    protected function addWarning() {
        if ($this->messages) {
            $tipo = $this->getIsValid() ? 'warning' : 'error';
            $log = new \WE\ReportBundle\Entity\ActivacionLog();
            $log->setActivacion($this->activacion);
            $log->setTipo($tipo);
            $log->setContenido($this->messages);

            $this->em->persist($log);
            $this->em->flush();
        }
    }

    protected function getIsValid() {
        return $this->valid;
    }

    public function getCdcScope($proyecto, $user) {

        //PASARLE ESTE VALOR SI ES UN USUARIO QUE NO ES KAM
        $cdcs = array();

        if ($proyecto->getCdcs()->count()) {
            $cdcs = $proyecto->getCdcs();
        } else if ($proyecto->getPlazas()->count()) {
            $cdcs = $this->getCdcsByPlaza($proyecto->getPlazas());
        } else if ($proyecto->getRegiones()->count()) {
            $cdcs = $this->getCdcsByRegion($proyecto->getRegiones());
        }

        return $this->userCdcIntersection($cdcs, $user);
    }

    protected function getCdcsByPlaza($plazas) {
        $cdcs = array();
        foreach ($plazas as $plaza) {
            foreach ($plaza->getCdcs() as $cdc) {
                $cdcs[] = $cdc;
            }
        }
        return $cdcs;
    }

    protected function getCdcsByRegion($regiones) {
        $cdcs = array();
        foreach ($regiones as $region) {
            $cdcs = array_merge($cdcs, $this->getCdcsByPlaza($region->getPlazas()));
        }
        return $cdcs;
    }

    protected function userCdcIntersection($cdcs, $user) {
        $return = $cdcs;
        if ($user->getCdcs()->count() > 0) {
            $return = array_intersect($user->getCdcs()->toArray(), $cdcs->toArray());
        }

        return $return;
    }

}
