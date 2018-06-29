<?php

namespace WE\ReportBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use WE\ReportBundle\Entity\Valor;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

/**
 * @Route("/")
 */
class DefaultController extends BaseController {

    /**
     * @Route("/preloader",name="preload")
     * @Template()
     */
    public function preloadAction() {
        set_time_limit(0);


        $em = $this->getDoctrine()->getManager();

        $centros_existent = $em->getRepository('ReportBundle:CDC')->findAll();

        if ($centros_existent) {
            foreach ($centros_existent as $centro) {
                $em->remove($centro);
            }
            $em->flush();
        }

        $reader = $this->container->get("arodiss.xls.reader");
        $iterator = $reader->getReadIterator("/Applications/MAMP/htdocs/we-reporte/web/cdc.xlsx");
        $kams = array();
        $ejecutivos = array();
        $plazas = array();
        $regiones = array();
        $cdcs = array();
        while ($iterator->valid()) {
            if ($iterator->current()[2]) {
                $kams[] = array('nombre' => $iterator->current()[2], 'region' => $iterator->current()[0]);
            }
            if ($iterator->current()[3] != "") {
                $ejecutivos[] = array('nombre' => $iterator->current()[3]);
            }
            if ($iterator->current()[1] != "") {
                $plazas[] = array('nombre' => $iterator->current()[1], 'region' => $iterator->current()[0]);
            }
            if ($iterator->current()[0] != "") {
                $regiones[] = $iterator->current()[0];
            }
            $cdcs[] = array('propietario' => $iterator->current()[7], 'idcliente' => $iterator->current()[6], 'ejecutivo' => $iterator->current()[3], 'nombre_cliente' => $iterator->current()[5], 'nombre' => $iterator->current()[4], 'plaza' => $iterator->current()[1]);
            $iterator->next();
        }

        $ejecutivos = array_unique($ejecutivos, SORT_REGULAR);
        $kams = array_unique($kams, SORT_REGULAR);
        $plazas = array_unique($plazas, SORT_REGULAR);
        $regiones = array_unique($regiones, SORT_REGULAR);
        $data_regiones = array();

        foreach ($regiones as $region) {
            $obejct = $this->addOrGetRegion($region);
            $data_regiones[$region] = $obejct;
            if (!$obejct->getId()) {
                $em->persist($obejct);
            }
        }

        $data_plazas = array();

        foreach ($plazas as $plaza) {
            $obejct = $this->addOrGetPlaza($plaza);
            $data_plazas[$plaza['nombre']] = $obejct;
            if (!$obejct->getId()) {
                $obejct->setRegion($data_regiones[$plaza['region']]);
                $em->persist($obejct);
            }
        }


        foreach ($kams as $kam) {
            $kam['region_object'] = @$data_regiones[$kam['region']];
            $obejct = $this->addOrGetKam($kam);
        }

        $data_ejecutivos = array();
        foreach ($ejecutivos as $ejecutivo) {
            $obejct = $this->addOrGetEjecutivo($ejecutivo);
            $data_ejecutivos[$ejecutivo['nombre']] = $obejct;
        }

        foreach ($cdcs as $cdc) {
            $centro = new \WE\ReportBundle\Entity\CDC();
            $centro->setNombre($cdc['nombre']);
            $centro->setCliente($cdc['nombre_cliente']);
            $centro->setClienteId($cdc['idcliente']);
            $centro->setPropietario($cdc['propietario']);
            if (@$data_plazas[$cdc['plaza']]) {
                $centro->setPlaza($data_plazas[$cdc['plaza']]);
            }
            $centro->setCapacidad(0);
            $em->persist($centro);

            if (@$data_ejecutivos[$cdc['ejecutivo']]) {
                $ejecutivo = $data_ejecutivos[$cdc['ejecutivo']];
                $ejecutivo->addCdc($centro);
            }
            $em->persist($ejecutivo);
        }

        $em->flush();

        return new \Symfony\Component\HttpFoundation\JsonResponse(array('status' => 'ok'));
    }

    /**
     * @Route("/",name="homepage")
     * @Template()
     */
    public function indexAction() {
        return $this->render('homepage/index.html.twig');
    }

    protected function addOrGetPlaza($plaza) {
        $em = $this->getDoctrine()->getManager();
        $plaza_object = $em->getRepository('ReportBundle:Plaza')->findOneBy(array('nombre' => $plaza['nombre']));
        if (!$plaza_object) {
            $plaza_object = new \WE\ReportBundle\Entity\Plaza();
            $plaza_object->setNombre($plaza['nombre']);
            $plaza_object->setAbreviacion($plaza['nombre']);
        }

        return $plaza_object;
    }

    protected function addOrGetRegion($region) {
        $em = $this->getDoctrine()->getManager();
        $region_object = $em->getRepository('ReportBundle:Region')->findOneBy(array('nombre' => $region));
        if (!$region_object) {
            $region_object = new \WE\ReportBundle\Entity\Region();
            $region_object->setNombre($region);
        }

        return $region_object;
    }

    protected function addOrGetEjecutivo($ejecutivo) {
        $em = $this->getDoctrine()->getManager();
        $kam_user = $em->getRepository('ReportBundle:Usuario')->findOneBy(array('nombre' => $ejecutivo['nombre']));

        if (!$kam_user) {
            $username = "prm_ejecutivo_" . time();
            $email = time() . '@prm.com';
            $userManager = $this->get('fos_user.user_manager');
            $kam_user = $userManager->createUser();
            $kam_user->setUsername($username);
            $kam_user->setEmail($email);
            $kam_user->setEmailCanonical($email);
            $kam_user->setEnabled(true);
            $kam_user->setNombre($ejecutivo['nombre']);
            $kam_user->addRole("ROLE_USER_EJECUTIVO");
            $kam_user->setPlainPassword($username);
            $userManager->updateUser($kam_user, false);
        }

        return $kam_user;
    }

    protected function addOrGetKam($kam) {
        $em = $this->getDoctrine()->getManager();
        $kam_user = $em->getRepository('ReportBundle:Usuario')->findOneBy(array('nombre' => $kam['nombre']));
        if (!$kam_user) {
            $username = "prm_kam_" . time();
            $email = time() . '@prm.com';
            $userManager = $this->get('fos_user.user_manager');
            $kam_user = $userManager->createUser();
            $kam_user->setUsername($username);
            $kam_user->setNombre($kam['nombre']);
            $kam_user->setEmail($email);
            $kam_user->setEmailCanonical($email);
            $kam_user->setEnabled(true);
            $kam_user->addRole("ROLE_USER_KAM");
            if ($kam['region_object']) {
                $kam_user->setRegion($kam['region_object']);
            }
            $kam_user->setPlainPassword($username);
            $userManager->updateUser($kam_user, false);
        }

        return $kam_user;
    }

    /**
     *
     * @Route("/calendario", name="calendar")
     */
    public function calendarAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $allBrand = $em->getRepository('ReportBundle:Marca')->findAll();

        $brand = null;
        if ($request->get('brand_id')) {
            $brand = $em->getRepository('ReportBundle:Marca')->find($request->get('brand_id'));
        }

        $activaciones = $this->findActivacionesByRank($em, $brand);

        return $this->render('ReportBundle:calendar:index.html.twig', array('activaciones' => $this->prepareActivaciones($activaciones), 'brands' => $allBrand));
    }

    /**
     * @Route("/marcas/set/{id}", name="cambiar_marca")
     */
    public function cambiarMarcaAction($id) {
        $em = $this->getDoctrine()->getManager();
        $usuario = $this->getUser();
        $brand = $em->getRepository('ReportBundle:Marca')->find($id);
        $usuario->setMarcas($brand);
        $em->persist($usuario);
        $em->flush();
        return $this->redirect($this->generateUrl('homepage'));
    }

    /**
     *
     * @Route("/marcas", name="marcas_grid")
     */
    public function gridAction() {
        $usuario = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $marcas = $em->getRepository('ReportBundle:Marca')->findByData($usuario, 100);

        return $this->render('ReportBundle:activaciones:grid.html.twig', array(
                    'entities' => $marcas,
        ));
    }

    protected function prepareActivaciones($activaciones) {
        $return = array();
        foreach ($activaciones as $activacion) {
            $element = array('url' => $this->generateUrl('activacion_show', array('id' => $activacion->getId())), 'title' => '@' . $activacion->getCDC()->getNombre(), 'start' => $activacion->getFecha());
            $return[] = $element;
        }

        return $return;
    }

    public function navMarcasAction($max = 2) {
        $usuario = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $marcas = $em->getRepository('ReportBundle:Marca')->findByData($usuario, $max);
        return $this->render('ReportBundle:Default:_marcas_nav.html.twig', array('entities' => $marcas));
    }

}
