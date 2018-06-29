<?php

namespace WE\ReportBundle\Controller\Api;
use WE\ReportBundle\Controller\BaseController as BaseController;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use WE\ReportBundle\Entity\Activacion;
use WE\ReportBundle\Entity\Proyecto;
use WE\ReportBundle\Entity\ProyectoAsignacionCdc;
use WE\ReportBundle\Entity\ProyectoAsignacionPlaza;
use WE\ReportBundle\Entity\ProyectoAsignacionRegion;

/**
 * @Route("/api")
 */
class DefaultController extends BaseController {

    const PUBLIC_PASSWORD = "SSMAPFRECARIBEANDO2018";

    /**
     * @Route("/", name="api_index")
     */
    public function indexAction(Request $request) {
        $response = array('status' => 'ok');
        return $this->doResponse($response, $request);
    }

    /**
     * @Route("/usuario", name="api_usuarios")
     */
    public function usuarioAction(Request $request) {

        $username = $request->get('username');
        $password = $request->get('password');
        $repository = $this->getDoctrine()->getRepository('ReportBundle:Usuario');

        $entity = $repository->findOneBy(array('username' => $username));

        $isValid = $entity?$this->get('security.password_encoder')
            ->isPasswordValid($entity, $password):false;


        $data = array();
        if ($entity && $isValid) {
            $data = array('usuario' => $entity);
            // if ($entity->getNombre() == null) {
            //     array_push($data, array("nombre" => ""));
            // }
            $response = array('status' => 'ok', 'data' => $data);
        } else {
            $response = array('status' => 'error', 'data' => $data);
        } 

        return $this->doResponse($response, $request);
    }

    /**
     * @Route("/user-notification", name="api_user_notification")
     */
    public function usnotificationAction(Request $request) {

        $user_id = $request->get('user_id');
        
        $repository = $this->getDoctrine()->getRepository('ReportBundle:Notificacion');

        $entity = $repository->findBy(array(
            'user_to' => $user_id,
            'status' => 0
        ),
            array(
                'fecha' => 'DESC'
            )
            ,5
        );

        $data = array(); 
        
        if ($entity) {
            $data = array('notification' => $entity);
            $response = array('status' => 'ok', 'data' => $data);
        }else {
            $response = array('status' => 'empty', 'data' => $data);
        } 

        return $this->doResponse($response, $request);
    }

    /**
     * @Route("/read-notification", name="api_read_notification")
     */
    public function readAction(Request $request) {

        $notification_id = $request->get('notification_id');
        $em = $this->getDoctrine()->getManager();
        $notif = $em->getRepository('ReportBundle:Notificacion')->findOneBy(array('id' => $notification_id));        

        $notif->setStatus(1);
        $em->persist($notif);
        $em->flush();
        $response = array('status' => 'ok');
        
        //Setear el status 1


        return $this->doResponse($response, $request);
    }

    /**
     * @Route("/activacion", name="api_activacion")
     */
    public function activacionAction(Request $request) {
       $user_id = $request->get('user_id');

        $em = $this->getDoctrine()->getManager();
        $user = $this->getUserObject($user_id);

        $brand = null;
        $activaciones= null;

        if (in_array('ROLE_USER_CUENTA', $user->getRoles()) || in_array('ROLE_USER_KAM', $user->getRoles()) || in_array('ROLE_USER_PRODUCTOR', $user->getRoles())) {
            $activaciones = $em->getRepository('ReportBundle:Activacion')->findByBrand($brand, null, $user);
        } else if ($this->get('security.authorization_checker')->isGranted('ROLE_USER_SUPERVISOR') && !$this->get('security.authorization_checker')->isGranted('ROLE_USER_PRODUCTOR')) {
            $activaciones = $em->getRepository('ReportBundle:Activacion')->findByBrand($brand, array(2, 6, 7,10,13,14,4), $user);
        } else {
            $activaciones = $em->getRepository('ReportBundle:Activacion')->findByBrand($brand);
        }

        $data = array(); 
        
        if ($activaciones) {
            $data = array('activaciones' => $activaciones);
            $response = array('status' => 'ok', 'data' => $data);
        }else {
            $response = array('status' => 'empty', 'data' => $data);
        } 

        return $this->doResponse($response, $request);
    }



    /**
     * @Route("/get-activacion", name="api_get_activacion")
     */
    public function getActivacionAction(Request $request) {

        $activacion_id = $request->get('activacion_id');
        $em = $this->getDoctrine()->getManager();
        $activacion = $em->getRepository('ReportBundle:Activacion')->findOneBy(array('id' => $activacion_id));        

        $data = array();
        
        if ($activacion) {
            $data = array('activacion' => $activacion);
            $response = array('status' => 'ok', 'data' => $data);
        }else {
            $response = array('status' => 'error', 'data' => $data);
        }


        return $this->doResponse($response, $request);
    }

    /**
     * @Route("/get-productores-list", name="api_get_prod_list")
     */
    public function getProdListAction(Request $request) {

        $user_id = $request->get('user_id');
        $em = $this->getDoctrine()->getManager();
        $productores = $em->getRepository('ReportBundle:Usuario')->getProductoresList($this->getUserObject($user_id)->getAgencia());

        $data = array();
        
        if ($productores) {
            $data = array('productores' => $productores);
            $response = array('status' => 'ok', 'data' => $data);
        }else {
            $response = array('status' => 'error', 'data' => $data);
        }


        return $this->doResponse($response, $request);
    }

    /**
     * @Route("/get-supervisores-list", name="api_get_superv_list")
     */
    public function getSupervListAction(Request $request) {

        $user_id = $request->get('user_id');
        $em = $this->getDoctrine()->getManager();
        $supervisores = $em->getRepository('ReportBundle:Usuario')->getSupervisoresList($this->getUserObject($user_id)->getAgencia());

        $data = array();
        
        if ($supervisores) {
            $data = array('supervisores' => $supervisores);
            $response = array('status' => 'ok', 'data' => $data);
        }else {
            $response = array('status' => 'error', 'data' => $data);
        }


        return $this->doResponse($response, $request);
    }

    /**
     * @Route("/set-productor-activacion", name="api_set_prod_act")
     */
    public function setProdActAction(Request $request) {

        $activacion_id = $request->get('activacion_id');
        $producer_id = $request->get('producer_id');

        $em = $this->getDoctrine()->getManager();

        $productor = $this->getUserObject($producer_id);

        $activacion = $em->getRepository('ReportBundle:Activacion')->findOneBy(
            array(
                'id' => $activacion_id
            )
        );
        $status = $em->getRepository('ReportBundle:ActivacionStatus')->find(8);
        $activacion->setStatus($status);

        $existe = false;

        foreach ($activacion->getUsuarios() as $usuario) {
            if ($usuario->getUsuario() == $productor){
                $existe = true;
            }
        }

        if (!$existe) {
            $activacion->addUsuario($this->addUserToActivacion('Productor', $productor));
        }
        //OJO con esta notificación, truena en local
        $this->container->get('status_generator')->setProducers($activacion);
        $em->flush();

        $AsigProdData = array(
            'done' => true
        );

        $AsigError = array(
            'done' => false
        );
        
        if (!$existe) {
            $data = array('asignacion' => $AsigProdData);
            $response = array('status' => 'ok', 'data' => $data);
        }else {
            $response = array('status' => 'El usuario ya está asignado', 'data' => $dataErr);
        }


        return $this->doResponse($response, $request);
    }

    /**
     * @Route("/set-supervisor-activacion", name="api_set_superv_act")
     */
    public function setSupervAction(Request $request) {

        $activacion_id = $request->get('activacion_id');
        $superv_id = $request->get('producer_id');

        $em = $this->getDoctrine()->getManager();

        $supervisor = $this->getUserObject($superv_id);

        $activacion = $em->getRepository('ReportBundle:Activacion')->findOneBy(
            array(
                'id' => $activacion_id
            )
        );

        $supervisores = $this->findUsers('Supervisor', $activacion);

        if (!$supervisores->count()) {
                $status = $em->getRepository('ReportBundle:ActivacionStatus')->find(6);
                $activacion->setStatus($status);
        }

        foreach ($supervisores as $sup) {
            $activacion->removeUsuario($sup);
            $em->remove($sup);
            $em->flush();
        }

        if (!$supervisores->count()) {
            $activacion->addUsuario($this->addUserToActivacion('Supervisor', $supervisor));
        }
        //OJO con esta notificación, truena en local
        $this->container->get('status_generator')->setProducers($activacion);
        $em->flush();

        $AsigProdData = array(
            'done' => true
        );

        $AsigError = array(
            'done' => false
        );
        
        if (!$supervisores->count()) {
            $data = array('asignacion' => $AsigProdData);
            $response = array('status' => 'ok', 'data' => $data);
        }else {
            $response = array('status' => 'El usuario ya está asignado', 'data' => $dataErr);
        }


        return $this->doResponse($response, $request);
    }

    /**
     * @Route("/get-form-activacion", name="api_form_activacion")
     */
    public function formActivacionAction(Request $request) {

        $em = $this->getDoctrine()->getManager();
        
        $user_id = $request->get('user_id');
        $userRepository = $this->getDoctrine()->getRepository('ReportBundle:Usuario');
        $user = $userRepository->findOneBy(array('id' => $user_id));

        // $activacion = new Activacion();
        $activacion = $this->getDoctrine()->getRepository('ReportBundle:Activacion');

        $rs = $em->getRepository('ReportBundle:Proyecto')->findProyectosByUser($user);

        $data = array();
        
        if ($rs) {
            $data = array('formulario' => $rs);
            $response = array('status' => 'ok', 'data' => $data);
        }else {
            $response = array('status' => 'error', 'data' => $data);
        }


        return $this->doResponse($response, $request);
    }

     /**
     * @Route("/get-activaciones-tipo", name="api_activ_tipo")
     */
    public function getActivTipoAction(Request $request) {
        $project_id = $request->get('project_id');

        $em = $this->getDoctrine()->getManager();

        $projectRepository = $this->getDoctrine()->getRepository('ReportBundle:Proyecto');

        $project = $projectRepository->findOneBy(array('id' => $project_id));

        $data = array(); 
        
        if ($project) {
            $data = array('tipo' => $project->getActivacionesTipo());
            $response = array('status' => 'ok', 'data' => $data);
        }else {
            $response = array('status' => 'empty', 'data' => $data);
        } 

        return $this->doResponse($response, $request);
    }

     /**
     * @Route("/get-cdc-scope", name="api_get_cdcscope")
     */
    public function CDCscopeAction(Request $request) {
        $project_id = $request->get('project_id');
        $user_id = $request->get('user_id');

        $em = $this->getDoctrine()->getManager();

        $projectRepository = $this->getDoctrine()->getRepository('ReportBundle:Proyecto');
        $userRepository = $this->getDoctrine()->getRepository('ReportBundle:Usuario');

        $project = $projectRepository->findOneBy(array('id' => $project_id));
        $user = $userRepository->findOneBy(array('id' => $user_id));

        $cdcs = $this->container->get('proyect_validator')->getCdcScope($project, $user);

        $data = array(); 
        
        if ($cdcs) {
            $data = array('cdcs' => $cdcs);
            $response = array('status' => 'ok', 'data' => $data);
        }else {
            $response = array('status' => 'empty', 'data' => $data);
        } 

        return $this->doResponse($response, $request);
    }

    /**
     * @Route("/new-project-form", name="api_new_project_f")
     */
    public function newProjectFormAction(Request $request) {

        $em = $this->getDoctrine()->getManager();

        $brandRepository = $this->getDoctrine()->getRepository('ReportBundle:Marca')->findAll();

        $agencyRepository = $this->getDoctrine()->getRepository('ReportBundle:Agencia')->findAll();

        $typeActivRepository = $this->getDoctrine()->getRepository('ReportBundle:ActivacionTipo')->findAll();

        $regionsRepository = $this->getDoctrine()->getRepository('ReportBundle:Region')->findAll();

        $mallRepository = $this->getDoctrine()->getRepository('ReportBundle:Plaza')->findAll();

        $CdcRepository = $this->getDoctrine()->getRepository('ReportBundle:CDC')->findAll();


        $data = array(
            'marcas' => $brandRepository,
            'agencias' => $agencyRepository,
            'tipo_activacion' => $typeActivRepository,
            'regiones' => $regionsRepository,
            'plazas' => $mallRepository,
            'cdc' => $CdcRepository
        );
        
        if ($data) {
            $response = array('status' => 'ok', 'data' => $data);
        }else {
            $response = array('status' => 'error', 'data' => $data);
        } 

        return $this->doResponse($response, $request);
    }  

    /**
     * @Route("/get-mall-cdc-byreg", name="api_get_mall_cdc_byreg")
     */
    public function getMallCdcByregAction(Request $request) {
        $region_id = $request->get('region_id');
        $plaza_id = $request->get('plaza_id'); 

        $em = $this->getDoctrine()->getManager();

        $query = null;
        $allRegions = explode(",", $region_id);
        $allMalls = explode(",", $plaza_id);
        $data = array();

        // *** Dev | versión multi región
        if ($region_id != null) {
            foreach ($allRegions as $key) {
                $query = $this->getDoctrine()->getRepository('ReportBundle:Plaza')->findBy(
                    array(
                        'region' => $key
                    )
                );
                foreach ($query as $res) {
                    array_push($data, $res);
                }
            }
        }else if ($plaza_id != null) {
            foreach ($allMalls as $key) {
                $query = $this->getDoctrine()->getRepository('ReportBundle:CDC')->findBy(
                    array(
                        'plaza' => $key
                    )
                );
                foreach ($query as $res) {
                    array_push($data, array(
                        'id' => $res->getId(),
                        'nombre' => $res->getNombre()
                    ));
                }
            }
        }


        //  *** Estable | Versión sin multiregión        
        // if ($region_id != null) {
        //     $query = $this->getDoctrine()->getRepository('ReportBundle:Plaza')->findBy(
        //         array(
        //             'region' => $region_id
        //         )
        //     );
        // }else if ($plaza_id != null) {
        //     $query = $this->getDoctrine()->getRepository('ReportBundle:CDC')->findBy(
        //         array(
        //             'plaza' => $plaza_id
        //         )
        //     );
        // }

        
        
        if ($data) {
            $response = array('status' => 'ok', 'data' => $data);
        }else {
            $response = array('status' => 'error', 'data' => $data);
        } 

        return $this->doResponse($response, $request);
    }

    /**
     * @Route("/send-project-form", name="api_send_project_f")
     */
    public function sendProjectFormAction(Request $request) {
       $user_id = $request->get("user_id");
       $project_name = $request->get("project_name");
       $project_dateIni = $request->get("project_dateIni");
       $datein = new \DateTime($project_dateIni);
       $project_dateFin = $request->get("project_dateFin");
       $datefn = new \DateTime($project_dateFin);

       $project_desc = $request->get("project_desc");
       $project_totalActiv = $request->get("project_totalActiv");
       $project_totalSale = $request->get("project_totalSale");
       $project_impact = $request->get("project_impact");
       $project_tasting = $request->get("project_tasting");
       $project_maxActiv = $request->get("project_maxActiv");
        
       $project_region = $request->get("project_region");
       $project_plaza = $request->get("project_plaza");
       $project_cdc = $request->get("project_cdc");
       $project_cancel = $request->get("project_cancel");
       $project_typeSale = $request->get("project_typeSale");
       $project_typeActiv = $request->get("project_typeActiv");
       $project_agency = $request->get("project_agency");
       $project_brand = $request->get("project_brand");
 
        $em = $this->getDoctrine()->getManager();
        $projectRepository = new Proyecto();

        $projectRepository->setMarca($this->getBrandObject($project_brand));
        $projectRepository->setNombre($project_name);
        $projectRepository->setFechaInicio($datein);
        $projectRepository->setFechaFin($datefn);
        $projectRepository->setTotalActivaciones($project_totalActiv);
        $projectRepository->setMaximoPlaza($project_maxActiv);
        $projectRepository->setTiempoCancelacion($project_cancel);
        $projectRepository->setAgencia($this->getAgencyObject($project_agency));
        $projectRepository->setKpiTipo($project_typeActiv);
        $projectRepository->setKpiTotal($project_totalSale);
        $projectRepository->setResponsable($this->getUserObject($user_id));
        $projectRepository->setDescripcion($project_desc);
        $projectRepository->setKpiImpactos($project_impact);
        $projectRepository->setKpiDegustaciones($project_tasting);
        // Multiregion
        $allRegions = explode(",", $project_region);
        foreach ($allRegions as $id) {
            if($id){
                $projectRepository->addRegione($this->getRegObject($id));
            }
        }
        $allMalls = explode(",", $project_plaza);
        foreach ($allMalls as $id) {
            if($id){
                $projectRepository->addPlaza($this->getPlzObject($id));
            }
        }
        $allCDC = explode(",", $project_cdc);
        foreach ($allCDC as $id) {
            if($id){
                $projectRepository->addCdc($this->getCdcObject($id));
            }
        }
       // $this->getPlazasByRegion($project_region)
        // $projectRepository->addPlaza($this->getPlzObject($project_plaza));
        // $projectRepository->addCdc($this->getCdcObject($project_cdc));
        $projectRepository->addActivacionesTipo($this->getActivTypeObject($project_typeActiv));

        $em->persist($projectRepository);
        $em->flush();

        $this->container->get('status_generator')->setProyecto($projectRepository);
        
        //Asignaciones

        $assign_cdc = array();
        $assign_total_cdc = array();
        $assign_region = array();
        $assign_total_region = array();
        $assign_plaza = array();
        $assign_total_plaza = array();

        foreach ($request->request as $key => $value) {
            if ($key == "assign_cdc") {
                $assign_cdc = explode(",", $value);
            }
            if ($key == "assign_total_cdc") {
                $assign_total_cdc = explode(",", $value);
            }
            if ($key == "assign_region") {
                $assign_region = explode(",", $value);
            }
            if ($key == "assign_total_region") {
                $assign_total_region = explode(",", $value);
            }
            if ($key == "assign_plaza") {
                $assign_plaza = explode(",", $value);
            }
            if ($key == "assign_total_plaza") {
                $assign_total_plaza = explode(",", $value);
            }
        }

        if (count($assign_cdc)) {
            foreach ($assign_cdc as $key => $value) {
                $assign_cdc_repo = new ProyectoAsignacionCdc();
                $assign_cdc_repo->setTotal($assign_total_cdc[$key]);
                $assign_cdc_repo->setCdc($this->getCdcObject($value));
                $assign_cdc_repo->setProyecto($projectRepository);
                $em->persist($assign_cdc_repo);
            }
            $em->flush();
        }
        if (count($assign_region)) {
            foreach ($assign_region as $key => $value) {
                $assign_region_repo = new ProyectoAsignacionRegion();
                $assign_region_repo->setTotal($assign_total_region[$key]);
                $assign_region_repo->setRegion($this->getRegObject($value));
                $assign_region_repo->setProyecto($projectRepository);
                $em->persist($assign_region_repo);
            }
            $em->flush();
        }
        if (count($assign_plaza)) {
            foreach ($assign_plaza as $key => $value) {
                $assign_plaza_repo = new ProyectoAsignacionPlaza();
                $assign_plaza_repo->setTotal($assign_total_plaza[$key]);
                $assign_plaza_repo->setPlaza($this->getPlzObject($value));
                $assign_plaza_repo->setProyecto($projectRepository);
                $em->persist($assign_plaza_repo);
            }
            $em->flush();
        }

        $data = array(
            'id' => $projectRepository->getId()
        );

        // MANDO RESPUESTA
        
        if ($projectRepository) {
            $response = array('status' => 'ok', 'data' => $data);
        }else {
            $response = array('status' => 'error', 'data' => $data);
        } 

        return $this->doResponse($response, $request);
    }  

    /**
     * @Route("/send-form-activacion", name="api_send_activacion")
     */
    public function sendActivacionFormAction(Request $request) {

        $project_id = $request->get('project_id');
        $project_date = $request->get('project_date');

        $date = new \DateTime($project_date );
        $new_date_format = $date->format('Y-m-d H:i:s');

        $tipo_id = $request->get('tipo_id');
        $cdc_id = $request->get('cdc_id');
        $user_id = $request->get('user_id');

        $activacion = new Activacion();
        $activacion->setProyecto($this->getProjectObject($project_id));
        $activacion->setFecha($date);
        $activacion->setTipo($this->getTipoObject($tipo_id));
        $activacion->setCdc($this->getCdcObject($cdc_id));
        

        // $em = $this->getDoctrine()->getManager();

        $newActivacion = $this->newActivacionForm($activacion, $this->getUserObject($user_id));
        $em = $this->getDoctrine()->getManager();
        $em->persist($newActivacion);
        $em->flush();

        $this->container->get('status_generator')->setStatus($activacion);

        $this->container->get('proyect_validator')->setWarning($activacion);

        $activacionData = array(
            'id' => $activacion->getId(),
            'done' => true
        );
        $activacionError = array(
            'id' => 0,
            'done' => false
        );

        $data = array(); 
        
        if ($activacionData) {
            $data = array('activacion' => $activacionData);
            $response = array('status' => 'ok', 'data' => $data);
        }else {
            $data = array('Error' => $activacionError);
            $response = array('status' => 'error', 'data' => $data);
        } 

        return $this->doResponse($response, $request);
    }

    /**
     * @Route("/show-project-plan", name="api_show_project")
     */
    public function showProjectAction(Request $request) {

        $project_id = $request->get('project_id');

        $project = $this->getProjectObject($project_id); 
        
        if ($project) {
            $response = array('status' => 'ok', 'data' => $project);
        }else {
            $response = array('status' => 'error', 'data' => 'No se encontró proyecto con el id: '.$project_id);
        } 

        return $this->doResponse($response, $request);
    }


    protected function getUserObject($user_id) {
        $userRepository = $this->getDoctrine()->getRepository('ReportBundle:Usuario');
        return $user = $userRepository->findOneBy(array('id' => $user_id));
    }
    protected function getBrandObject($brand_id) {
        $brandRepository = $this->getDoctrine()->getRepository('ReportBundle:Marca');
        return $brand = $brandRepository->findOneBy(array('id' => $brand_id));
    }
    protected function getAgencyObject($agency_id) {
        $agencyRepository = $this->getDoctrine()->getRepository('ReportBundle:Agencia');
        return $agency = $agencyRepository->findOneBy(array('id' => $agency_id));
    }
    protected function getUsuarioActivacionObject($user_id) {
        $userRepository = $this->getDoctrine()->getRepository('ReportBundle:Usuario');
        return $user = $userRepository->findOneBy(array('id' => $user_id));
    }
    protected function getProjectObject($project_id) {
        $projectRepository = $this->getDoctrine()->getRepository('ReportBundle:Proyecto');
        return $proyecto = $projectRepository->findOneBy(array('id' => $project_id));
    }

    protected function getTipoObject($tipo_id) {
        $tipoRepository = $this->getDoctrine()->getRepository('ReportBundle:ActivacionTipo');
        return $tipo = $tipoRepository->findOneBy(array('id' => $tipo_id));
    }
    protected function getCdcObject($cdc_id) {
        $cdcRepository = $this->getDoctrine()->getRepository('ReportBundle:CDC');
        return $cdc = $cdcRepository->findOneBy(array('id' => $cdc_id));
    }
    protected function getRegObject($reg_id) {
        $regRepository = $this->getDoctrine()->getRepository('ReportBundle:Region');
        return $region = $regRepository->findOneBy(array('id' => $reg_id));
    }
    protected function getPlzObject($plz_id) {
        $plzRepository = $this->getDoctrine()->getRepository('ReportBundle:Plaza');
        return $plaza = $plzRepository->findOneBy(array('id' => $plz_id));
    }
    protected function getActivTypeObject($at_id) {
        $atRepository = $this->getDoctrine()->getRepository('ReportBundle:ActivacionTipo');
        return $at = $atRepository->findOneBy(array('id' => $at_id));
    }
    protected function getPlazasByRegion($regions) {
        $allRegions = explode(",", $regions);
        $reg = array();
        $datos= array();
        foreach ($allRegions as $region_id) {
            $query = $this->getDoctrine()->getRepository('ReportBundle:Plaza')->findBy(
                array(
                    'region' => $region_id
                )
            );
            $reg[]= $query;
        }
        return $reg;
    }

    /**
     * @Route("/all-users", name="api_users")
     */
    public function allusersAction(Request $request) {
        $user_id = $request->get('user_id');

        $em = $this->getDoctrine()->getManager();
        $userRepository = $this->getDoctrine()->getRepository('ReportBundle:Usuario');
        $user = $userRepository->findOneBy(array('id' => $user_id));
        if ($user->getAgencia() == null) {
            $allUsers = $em->getRepository('ReportBundle:Usuario')->findAll();
        }else{
            $allUsers = $em->getRepository('ReportBundle:Usuario')->findBy(array('agencia' => $user->getAgencia()->getId()));
        }

        $data = array(); 
         
        if ($allUsers) {
            $data = array('usuarios' => $allUsers);
            $response = array('status' => 'ok', 'data' => $data);
        }else {
            $response = array('status' => 'empty', 'data' => $data);
        } 

        return $this->doResponse($response, $request);
    } 

    /**
     * @Route("/proyecto", name="api_proyecto")
     */
    public function projectAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $proyectos = $em->getRepository('ReportBundle:Proyecto')->findBy(array(), array('id' => 'DESC'));

        $data = array(); 
         
        if ($proyectos) {
            $data = array('proyectos' => $proyectos);
            $response = array('status' => 'ok', 'data' => $data);
        }else {
            $response = array('status' => 'empty', 'data' => $data);
        }

        return $this->doResponse($response, $request);
    }

    /**
     * @Route("/get-status-activacion", name="api_get_status_activ")
     */
    public function getStatusActivacionAction(Request $request) {
        $activacion_id = $request->get('activacion_id');
        $em = $this->getDoctrine()->getManager();
        $activacion = $em->getRepository('ReportBundle:Activacion')->find($activacion_id);

        //CAMBIAR EL NOMBRE POR EL ID EN CASO DE CAMBIAR EL TEXTO DE STATUS
        $data = array(
            'status' => $activacion->getStatus()->getNombre()
        ); 
         
        if ($data) {
            $response = array('status' => 'ok', 'data' => $data);
        }else {
            $response = array('status' => 'empty', 'data' => $data);
        }

        return $this->doResponse($response, $request);
    }

     /**
     *
     * @Route("/calendario", name="api_calendar")
     */
    public function calendarrAction(Request $request) {
        $user_id = $request->get('user_id');
        $em = $this->getDoctrine()->getManager();

        $userRepository = $this->getDoctrine()->getRepository('ReportBundle:Usuario');

        $user = $userRepository->findOneBy(array('id' => $user_id));
        $brand = null;

        $activaciones = null;
        if (in_array('ROLE_USER_CUENTA', $user->getRoles()) || in_array('ROLE_USER_KAM', $user->getRoles()) || in_array('ROLE_USER_PRODUCTOR', $user->getRoles())) {
            $activaciones = $em->getRepository('ReportBundle:Activacion')->findByBrand($brand, null, $user, null, true);
        } else if ($this->get('security.authorization_checker')->isGranted('ROLE_USER_SUPERVISOR') && !$this->get('security.authorization_checker')->isGranted('ROLE_USER_PRODUCTOR')) {
            $activaciones = $em->getRepository('ReportBundle:Activacion')->findByBrand($brand, array(2, 6, 7,10,13,14,4), $user, null, true);
        } else {
            $activaciones = $em->getRepository('ReportBundle:Activacion')->findByBrand($brand, null, null, null, true);
        }

        $data = array(); 
         
        if ($activaciones) {
            $data = array('activaciones' => $activaciones);
            $response = array('status' => 'ok', 'data' => $data);
        }else {
            $response = array('status' => 'empty', 'data' => $data);
        } 

        return $this->doResponse($response, $request);
    }

    /**
     *
     * @Route("/set-status", name="api_op_activ")
     */
    public function operateactivacionAction(Request $request) {
      
        $activacion_id = $request->get('activacion_id');
        $status_id = $request->get('status_id');

        $em = $this->getDoctrine()->getManager();
        $activacion = $em->getRepository('ReportBundle:Activacion')->find($activacion_id);
        $status = $em->getRepository('ReportBundle:ActivacionStatus')->find($status_id);

        $activacion->setStatus($status);
        $em->persist($activacion);
        $em->flush();

        $this->container->get('status_generator')->setStatus($activacion);

        $response = array('status' => 'ok');

        return $this->doResponse($response, $request);
    }

    /**
     *
     * @Route("/get-data-capture-form", name="api_datacapture_form")
     */
    public function dataCaptureFormAction(Request $request) {
        $activacion_id = $request->get('activacion_id');
        $em = $this->getDoctrine()->getManager();
        $app = $em->getRepository('ReportBundle:Activacion')->find($activacion_id);
        $instrumento = $app->getProyecto()->getMarca()->getInstrumentos()->last();
        $data = array();
        $imgCheckin = array();
        $report = array();
        $group = array();


        //AÑADO IMÁGENES Y NOMBRES
        foreach ($app->getImages() as $value) {
            array_push($imgCheckin, 
                array(
                    "image" => $value->getImage(),
                    "name" => $value->getNombre()
                ));
        }
        $group["Imagenes"] = $imgCheckin;

        if ($app->getStatus()->getId() == 7 || $app->getStatus()->getId() == 4 || $app->getStatus()->getId() == 5 || $app->getStatus()->getId() == 10) {
            $group["preformulario"] = array(
                "Total" => $app->getTotal(),
                "Copeo" => $app->getCopeo(),
                "Botellas" => $app->getBotellas(),
            );
        }

        if ($app->getStatus()->getId() == 4 || $app->getStatus()->getId() == 5 || $app->getStatus()->getId() == 7 || $app->getStatus()->getId() == 12 || $app->getStatus()->getId() == 13 || $app->getStatus()->getId() == 14) {
            foreach ($app->getFilas() as $filas) {
                foreach ($filas->getValores() as $value) {
                    array_push($report, 
                        array(
                            "Label" => $value->getColumna()->getTexto(),
                            "Valor" => $value->getValor() == "" ? "0" : $value->getValor(),
                    ));
                }
            }
            $group["reporte"] = $report;
        }


        $data = $group;
        $response = array('status' => 'ok', 'data' => $data);

        return $this->doResponse($response, $request);
    }

    /**
     *
     * @Route("/capture-form", name="api_capture_form")
     */
    public function captureFormAction(Request $request) {
        $activacion_id = $request->get('activacion_id');
        $edit_type = $request->get('edit_type');

        $em = $this->getDoctrine()->getManager();
        $app = $em->getRepository('ReportBundle:Activacion')->find($activacion_id);
        $instrumento = $app->getProyecto()->getMarca()->getInstrumentos()->last();
        $form = array();
        $column = array();
        $headers = array(
            "Titulo" => $instrumento->getNombre(),
            "Instrucciones" => $instrumento->getInstrucciones()
        );

        // Verifica si regresa formulario para editar
        if ($edit_type != null) {
            //Construye Pre-formulario para editar
            if ($edit_type == 1) {
                $form[0]["Etiqueta"] = "Total";
                $form[0]["Tipo"] = "Entero";
                $form[0]["Valor"] = $app->getTotal();
                $form[1]["Etiqueta"] = "Copeo";
                $form[1]["Tipo"] = "Entero";
                $form[1]["Valor"] = $app->getCopeo();
                $form[2]["Etiqueta"] = "Botellas";
                $form[2]["Tipo"] = "Entero";
                $form[2]["Valor"] = $app->getBotellas();
                // NOTA: AÚN FALTA AÑADIR EL CAMPO DE IMAGEN
                // $image = new \WE\ReportBundle\Entity\Gallery();
                // $image->setNombre('Texto generico');
                // $app->addImage($image);
            }
            // Construye el formulario completo para editar
            if ($edit_type == 2) {

                $columnValue = array();
                $options = array();

                foreach ($app->getFilas() as $filas) {
                    foreach ($filas->getValores() as $value) {
                        $columnValue[] = $value->getValor() == "" ? "0" : $value->getValor();
                    }
                }

                foreach ($instrumento->getSecciones() as $seccion) {
                    foreach ($seccion->getColumnas() as $keyArray => $columna) {

                        $column["Id"] = $columna->getId();
                        $column["Etiqueta"] = $columna->getTexto();
                        $column["Tipo"] = $columna->getTipo()->getTipoColumna();
                        
                        if ($columna->getTipo()->getTipoColumna() == "Opciones") {
                            foreach ($columna->getItems() as $key) {
                                array_push($options, array(
                                    "Id" => $key->getId(),
                                    "Valor" => $key->getTexto()
                                ));
                            }
                            $column["item"] = $options;
                        }else{
                            $column["item"] = null;
                        }
                        $column["ValorColumna"] = $columnValue[$keyArray];
                        array_push($form, $column);
                    }
                }


            }

        // Construye nuevo formulario
        }else{
            if ($app->getTotal() == 0 && $app->getCopeo() == 0 && $app->getBotellas() == 0) {
                $form[0]["Etiqueta"] = "Total";
                $form[0]["Tipo"] = "Entero";
                $form[1]["Etiqueta"] = "Copeo";
                $form[1]["Tipo"] = "Entero";
                $form[2]["Etiqueta"] = "Botellas";
                $form[2]["Tipo"] = "Entero";
                // NOTA: AÚN FALTA AÑADIR EL CAMPO DE IMAGEN
                // $image = new \WE\ReportBundle\Entity\Gallery();
                // $image->setNombre('Texto generico');
                // $app->addImage($image);
            }else{
                $options = array();

                foreach ($instrumento->getSecciones() as $seccion) {
                    foreach ($seccion->getColumnas() as $columna) {

                        $column["Id"] = $columna->getId();
                        $column["Etiqueta"] = $columna->getTexto();
                        $column["Tipo"] = $columna->getTipo()->getTipoColumna();
                        
                        if ($columna->getTipo()->getTipoColumna() == "Opciones") {
                            foreach ($columna->getItems() as $key) {
                                array_push($options, array(
                                    "Id" => $key->getId(),
                                    "Valor" => $key->getTexto()
                                ));
                            }
                            $column["item"] = $options;
                        }else{
                            $column["item"] = null;
                        }
                        array_push($form, $column);
                    }
                }
            }
        }


        $data = array(); 
         
        if ($instrumento) {
            $data = array('columnas' => $form);
            $response = array('status' => 'ok', 'headers' => $headers, 'formulario' => $data);
        }else {
            $response = array('status' => 'empty', 'data' => $data);
        } 

        return $this->doResponse($response, $request);
    } 

    /**
     *
     * @Route("/pre-capture-form", name="api_pre_capture_form")
     */
    public function preCaptureFormAction(Request $request) {
        $Total = $request->get('Total');
        $Copeo = $request->get('Copeo');
        $Botellas = $request->get('Botellas');
        $edit = $request->get('edit_preform');
        $activacion_id = $request->get('activacion_id');

        $em = $this->getDoctrine()->getManager();
        $app = $em->getRepository('ReportBundle:Activacion')->find($activacion_id);

        $app->setTotal($Total);
        $app->setCopeo($Copeo);
        $app->setBotellas($Botellas);

        if ($edit == null) {
            $status = $em->getRepository('ReportBundle:ActivacionStatus')->find(9);
            $app->setStatus($status);
            $this->container->get('status_generator')->setStatus($app);
        }

        $em->persist($app);
        $em->flush();
        
        $response = array('status' => 'ok');

        return $this->doResponse($response, $request);
    }

    /**
     * @Route("/save-pre-reportform", name="api_save_pre_reportform")
     */
    public function savePreReportFormAction(Request $request) {

        $imageText = $request->get('imageText');
        $activacion_id = $request->get('activacion_id');

        $em = $this->getDoctrine()->getManager();
        $app = $em->getRepository('ReportBundle:Activacion')->find($activacion_id);
        //$instrumento = $app->getProyecto()->getMarca()->getInstrumentos()->last();

        $image = new \WE\ReportBundle\Entity\Gallery();
        $image->setNombre($imageText);
        $image->setImage('a');
        $fecha = new \DateTime('now');
        $image->setUpdatedAt($fecha);
        $app->addImage($image);

        $status = $em->getRepository('ReportBundle:ActivacionStatus')->find(9);
        $app->setStatus($status);

        $this->container->get('status_generator')->setStatus($app);

        $em->persist($app);
        $em->flush();

        $response = array('status' => 'ok');
        return $this->doResponse($response, $request);
    }

    /**
     *
     * @Route("/save-capture-form", name="api_save_capture_form")
     */
    public function savecaptureFormAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $app = $em->getRepository('ReportBundle:Activacion')->find($request->get('activacion_id'));
        $instrumento = $app->getProyecto()->getMarca()->getInstrumentos()->last();

        //borro filas
        if ($app->getFilas()->count()) {
            foreach ($app->getFilas()->last()->getValores() as $valor) {
                $em->remove($valor);
            }
        }
            $fila = new \WE\ReportBundle\Entity\Fila();
            $fila->setActivacion($app);
            
            foreach ($request->request as $key => $value) {

                if ($key != "activacion_id" && $key != "user_role") {
                    $columna = $em->getRepository('ReportBundle:Columna')->find($key);
                    if ($columna && $columna->getId()) {
                        $this->addValor($em, $columna, $fila, $value);
                    }
                }
            }

            $em->persist($fila);

            if ($request->get('user_role') == 'ROLE_USER_PRODUCTOR') {
                $status = $em->getRepository('ReportBundle:ActivacionStatus')->find(10);
            } else {
                $status = $em->getRepository('ReportBundle:ActivacionStatus')->find(4);
            }
            $app->setStatus($status);

            $em->persist($app);
            $em->flush();
            $this->container->get('status_generator')->setStatus($app);
            
            $response = array('status' => 'ok');
        

        return $this->doResponse($response, $request);
    }

    protected function doResponse($response, $request) {
        $serializer = $this->container->get('jms_serializer');
        $return = new Response($serializer->serialize($this->validateLogin($request) ? $response : $this->generateFailureLoginResponse(), 'json'));
        $return->headers->set('Content-Type', 'application/json');
        return $return;
    }

    protected function generateFailureLoginResponse() {
        return array('status' => 'error');
    }

    protected function validateLogin(Request $request) {
        return true;
        //return $request->get('public_password') == DefaultController::PUBLIC_PASSWORD;
    }

    /**
     *
     * @Route("/activacion/{activacion_id}/status/{status_id}", name="api_activaciones_operate")
     * @Method("GET")
     */
    public function operateAction($activacion_id, $status_id,Request $request) {
        //VALIDAR QUE NO VUELVA A ASIGAR EL VALOR DEFINIDO

        $em = $this->getDoctrine()->getManager();
        $activacion = $em->getRepository('ReportBundle:Activacion')->find($activacion_id);
        $status = $em->getRepository('ReportBundle:ActivacionStatus')->find($status_id);

        if ($status && $activacion) {
            $activacion->setStatus($status);
            $em->persist($activacion);
            $em->flush();

            $this->container->get('status_generator')->setStatus($activacion);
            $response = array('status' => 'ok');
        } else {
            $response = array('status' => 'error');
        }

        return $this->doResponse($response, $request);
    }
}