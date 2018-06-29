<?php

namespace WE\ReportBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use WE\ReportBundle\Entity\UsuarioActivacion;
use WE\ReportBundle\Entity\Valor;

class BaseController extends Controller {

    protected function findActivacionesByRank($em, $brand = null, $user = null) {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER_CUENTA') || $this->get('security.authorization_checker')->isGranted('ROLE_USER_KAM') || $this->get('security.authorization_checker')->isGranted('ROLE_USER_PRODUCTOR')) {
            $activaciones = $em->getRepository('ReportBundle:Activacion')->findByBrand($brand, null, $this->getUser());
        } else if ($this->get('security.authorization_checker')->isGranted('ROLE_USER_SUPERVISOR') && !$this->get('security.authorization_checker')->isGranted('ROLE_USER_PRODUCTOR')) {
            $activaciones = $em->getRepository('ReportBundle:Activacion')->findByBrand($brand, array(2, 6, 7,10,13,14,4), $this->getUser());
        } else {
            $activaciones = $em->getRepository('ReportBundle:Activacion')->findByBrand($brand);
        }

        return $activaciones;
    }

    protected function findProyectsByUser($em) {
        $proyectos = $em->getRepository('ReportBundle:Proyecto')->findBy(array('responsable' => $this->getUser()));
        return $proyectos;
    }

    protected function newActivacionForm($activacion, $user = null) {
        $em = $this->getDoctrine()->getManager();
        $activacion->setTotal(0);
        $activacion->setBotellas(0);
        $activacion->setCopeo(0);
        $status = $em->getRepository('ReportBundle:ActivacionStatus')->find(1);

        $activacion->addUsuario($this->addUserToActivacion('KAM', $user == null ? $this->getUser() : $user));
        $activacion->addUsuario($this->addUserToActivacion('Gerente de marca', $activacion->getProyecto()->getResponsable()));


        foreach ($this->fetchUsuarios(array('ROLE_USER_CUENTA'), $activacion->getProyecto()->getAgencia()) as $usuario) {
            $activacion->addUsuario($usuario);
        }

        $activacion->setStatus($status);

        return $activacion;
    }

    protected function addUserToActivacion($key, $usuario) {
        $usuario_activacion = new UsuarioActivacion();
        $usuario_activacion->setTipo($this->getUserTipoKey($key));
        $usuario_activacion->setUsuario($usuario);
        return $usuario_activacion;
    }

    protected function getUserTipoKey($key) {
        $datas = array('ROLE_USER_CUENTA' => 'Ejecutivo de cuenta');
        return isset($datas[$key]) ? $datas[$key] : $key;
    }

      protected function fetchUsuarios($selectable_users, $agencia = null) {
        $return = array();
        foreach ($selectable_users as $lookup_user) {
            $usuarios = $this->findUserByRole($lookup_user, $agencia);
            foreach ($usuarios as $usuario) {
                $return[] = $this->addUserToActivacion($lookup_user, $usuario);
            }
        }
        return $return;
    }

    protected function findUserByRole($role, $agencia = null) {
        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository('ReportBundle:Usuario')->findByRoleQuery($role);
        if ($agencia) {
            $query->andWhere('u.agencia = :agencia')
                    ->setParameter('agencia', $agencia);
        }
        return $query->getQuery()->getResult();
    }

    protected function findUsers($tipo, $activacion) {
        return $activacion->getUsuarios()->filter(function($usuario) use ($tipo) {
                    return $usuario->getTipo() === $tipo;
                });
    }

    protected function addValor($em, $columna, $fila, $item) {


        $valor = new Valor();
        $valor->setColumna($columna);
        $valor->setFila($fila);

        if (count($columna->getItems())) {
            if (is_int($item)) {
                $columna_item = $em->getRepository('ReportBundle:Item')->find($item);
            }else{
                $columna_item = $em->getRepository('ReportBundle:Item')->findOneBy(
                    array(
                        'valor' => $item
                    )
                );
            }
            
            if ($columna_item) {
                $valor->setItem($columna_item);
                $valor->setValor($columna_item->getValor());
            } else {
                $valor->setValor($this->parseData($item));
            }
        } else {
            $valor->setValor($this->parseData($item));
        }

        $em->persist($valor);
    }

    protected function parseData($valor) {

        if ($valor instanceof \DateTime) {
            $string = $valor->format('Y-m-d');
        } else {
            $string = $valor;
        }

        return $string;
    }



}
