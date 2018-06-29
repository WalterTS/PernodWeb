<?php

namespace WE\ReportBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MainController extends BaseController {

    // protected function findActivacionesByRank($em, $brand = null) {
    //     if ($this->get('security.authorization_checker')->isGranted('ROLE_USER_CUENTA') || $this->get('security.authorization_checker')->isGranted('ROLE_USER_KAM') || $this->get('security.authorization_checker')->isGranted('ROLE_USER_PRODUCTOR')) {
    //         $activaciones = $em->getRepository('ReportBundle:Activacion')->findByBrand($brand, null, $this->getUser());
    //     } else if ($this->get('security.authorization_checker')->isGranted('ROLE_USER_SUPERVISOR') && !$this->get('security.authorization_checker')->isGranted('ROLE_USER_PRODUCTOR')) {
    //         $activaciones = $em->getRepository('ReportBundle:Activacion')->findByBrand($brand, array(2, 6, 7,10,13,14,4), $this->getUser());
    //     } else {
    //         $activaciones = $em->getRepository('ReportBundle:Activacion')->findByBrand($brand);
    //     }

    //     return $activaciones;
    // }

    // protected function findProyectsByUser($em) {
    //     $proyectos = $em->getRepository('ReportBundle:Proyecto')->findBy(array('responsable' => $this->getUser()));
    //     return $proyectos;
    // }

}
