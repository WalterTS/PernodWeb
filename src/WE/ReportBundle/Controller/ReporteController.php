<?php

namespace WE\ReportBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use WE\ReportBundle\Entity\Valor;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\JsonResponse;
use WE\ReportBundle\Form\ReporteFilterType;

/**
 * @Route("/reporte")
 */
class ReporteController extends BaseController {

    /**
     *
     * @Route("/filter", name="ajax_filter")
     * @Method("GET")
     */
    public function plazaFilterAction(Request $request) {
        $as = $this->get('tetranz_select2entity.autocomplete_service');
        $result = $as->getAutocompleteResults($request, ReporteFilterType::class);
        return new JsonResponse($result);
    }

    /**
     *
     * @Route("/{activacion_id}/{status_id}", name="activaciones_operate")
     * @Method("GET")
     */
    public function operateAction($activacion_id, $status_id) {
        //VALIDAR QUE NO VUELVA A ASIGAR EL VALOR DEFINIDO

        $em = $this->getDoctrine()->getManager();
        $activacion = $em->getRepository('ReportBundle:Activacion')->find($activacion_id);
        $status = $em->getRepository('ReportBundle:ActivacionStatus')->find($status_id);

        $activacion->setStatus($status);
        $em->persist($activacion);
        $em->flush();

        $this->container->get('status_generator')->setStatus($activacion);

        return $this->redirect($this->generateUrl('activacion_show', array('id' => $activacion->getId())));
    }

    /**
     * @Route("/",name="reporte_index")
     * @Template()
     */
    public function indexAction(Request $request) {

        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER_GERENTE')) {
            $type = 'WE\ReportBundle\Form\ReporteFilterType';
            $user = null;
        } else {
            $type = 'WE\ReportBundle\Form\ReporteFilterGeneralUserType';
            $user = $this->getUser();
        }

        $form = $this->createForm($type);
        $form->handleRequest($request);
        $parameters = null;
        if ($request->getMethod() == "POST") {
            $parameters = $form->getData();
        }
        $em = $this->getDoctrine()->getManager();
        $brand = $parameters['marca'];
        $status = $em->getRepository('ReportBundle:ActivacionStatus')->find(5);
        $activaciones = $em->getRepository('ReportBundle:Activacion')->findByBrand($brand, $status, $user, $parameters);

        $request->getSession()->set('activaciones', $activaciones);

        $all_activaciones = $em->getRepository('ReportBundle:Activacion')->findByBrand($brand, null, $user, $parameters);

        $instrumento = $brand ? $brand->getInstrumentos()->last() : null;

        $data = $this->getActivacionesTotales($all_activaciones, $activaciones);

        $activacion_valores_id = $instrumento ? $this->getColumnasReport('activacion_valores', $instrumento) : null;
        $activacion_valores = array();
        if ($activacion_valores_id) {
            $parameters['columnas'] = $activacion_valores_id;
            $activacion_valores = $em->getRepository('ReportBundle:Activacion')->findByValue($brand, null, $user, $parameters);
        }

        $promedios = array();
        $promedios_data = array();
        $promedios_id = $instrumento ? $this->getColumnasReport('promedios', $instrumento) : null;


        if ($promedios_id) {
            $parameters['columnas'] = $promedios_id;
            $valores = $em->getRepository('ReportBundle:Activacion')->findByPromedio($brand, null, $user, $parameters);
            foreach ($valores as $promedio) {
                $plaza = $promedio['abreviacion'];
                $promedios[$plaza][] = (float) $promedio['total'];
            }

            $columnas_promedio = $instrumento && $this->getColumnasByKey('promedios', $instrumento)->last() ? $this->getColumnasByKey('promedios', $instrumento)->last()->getColumnas() : null;

            $textos = array();
            foreach ($columnas_promedio as $columna) {
                $textos[] = $columna->getTexto();
            }

            $real_data = array();

            $index = 0;
            foreach ($textos as $texto) {
                foreach ($promedios as $promedio) {
                    $real_data[$index][] = $promedio[$index];
                }
                $index++;
            }


            $promedios_data = array('data' => $real_data, 'xlabels' => array_keys($promedios), 'labels' => $textos);
        }

        $ventas = $this->getVentas($brand, $user, $parameters);
        //$data_month = $this->getMonthData($activaciones);




        $parameters['instrumento'] = $instrumento;
        $share = $this->getShareData($brand, $status, $user, $parameters);
        $shareByCDC = $this->getShareDataByCDC($brand, $status, $user, $parameters);

        return array('form' => $form->createView(),
            'kpi_chart' => array('labels' => array('Botellas', 'Copeo'), 'values' => $this->getKpisChart($activaciones)),
            'activaciones_resume_data' => $this->getActivacionesResume($all_activaciones),
            'parameters' => $parameters,
            'brand' => $brand,
            //'data_month' => $data_month['data_month'],
            //'data_month_plaza' => array('plazas' => array_unique($data_month['plazas']),
            //'data' => $data_month['data_month_plaza']),
            'entities' => $activaciones,
            'resume_data' => array_reverse($data),
            'activacion_valores' => $activacion_valores,
            'promedios' => $promedios_data,
            'ventas' => $ventas,
            'promociones' => $this->getPromocionesData($brand, $user, $status, $parameters),
            'comentarios_chart' => $this->getComentariosChartData($activaciones),
            'ventas_chart' => $this->getVentasData($brand, $user, $parameters),
            'data_chart' => $this->getChartData($activaciones),
            'data_share_chart' => array(
                'labels' => array_keys($share),
                'values' => array_values($share)),
            'dataShareByCDCLabels' => array_values($shareByCDC['labels']),
            'dataShareByCDCData' => @$shareByCDC['data'] ? array_values($shareByCDC['data']) : array()
        );
    }

    protected function getKpisChart($activaciones) {
        $botellas = 0;
        $copeo = 0;
        foreach ($activaciones as $activacion) {
            $botellas += $activacion->getBotellas();
            $copeo += $activacion->getCopeo();
        }
        return array($botellas, $copeo);
    }

    protected function getComentariosChartData($activaciones) {
        $em = $this->getDoctrine()->getManager();

        $data = $em->getRepository('ReportBundle:ActivacionComentario')->findByComentariosMood($activaciones);

        $labels = array('Malo', 'Regular', 'Bueno');
        $data_chart = array();

        foreach ($labels as $label) {
            $found = array_search($label, array_column($data, 'label'));
            if ($found !== false) {
                $data_chart[$label] = $data[$found];
            }
        }

        $chart_data = array('values' => array_column($data_chart, 'total'), 'labels' => array_column($data_chart, 'label'));
        return $chart_data;
    }

    protected function getComentariosData($brand, $user, $status, $parameters) {
        $em = $this->getDoctrine()->getManager();
        $comentarios = null;
        $instrumento = $parameters['instrumento'];

        $comentarios_id = $instrumento ? $this->getColumnasReport('comentarios', $instrumento) : null;

        if ($comentarios_id) {
            $comentarios = $em->getRepository('ReportBundle:Activacion')->findByComentarios($brand, $status, $user, array_merge(array('comentarios_id' => $comentarios_id), $parameters));
        }

        return $comentarios;
    }

    protected function getPromocionesData($brand, $user, $status, $parameters) {
        $em = $this->getDoctrine()->getManager();
        $promociones = null;
        $instrumento = $parameters['instrumento'];

        $promociones_id = $instrumento ? $this->getColumnasReport('promociones', $instrumento) : null;

        if ($promociones_id) {
            $promociones = $em->getRepository('ReportBundle:Activacion')->findByPromotions($brand, $status, $user, array_merge(array('promociones_id' => $promociones_id), $parameters));
        }

        return $promociones;
    }

    protected function getVentasData($brand, $user, $parameters) {
        $data = array();
        $ventas = $this->getVentas($brand, $user, $parameters);

        foreach ($ventas as $venta) {
            $data[$venta['cdc']] = round($venta['ventas']);
        }

        return array('labels' => array_keys($data), 'values' => array_values($data));
    }

    protected function getShareData($brand, $status, $usuario, $parameters) {
        $em = $this->getDoctrine()->getManager();
        $share = array();
        $instrumento = $parameters['instrumento'];

        $share_ids = $instrumento ? $this->getColumnasReport('share', $instrumento) : null;

        if ($share_ids) {
            $data = $em->getRepository('ReportBundle:Activacion')->findByShare($brand, $status, $usuario, array_merge(array('columnas' => $share_ids), $parameters));
            $i = 0;
            foreach ($data as $sum) {
                $columna = $sum['texto'];
                $share[$columna][] = $i == 0 ? $sum['total'] * 1.20 : $sum['total'];
                $i++;
            }
        }

        return $share;
    }

    protected function getMonthData($activaciones) {
        $data_month = array();

        foreach ($activaciones as $activacion) {
            $month = $activacion->getFecha()->format('M');
            @$data_month[$month]['activaciones'] += 1;
            @$data_month[$month]['ventas'] += $activacion->getCopeo();
            @$data_month[$month]['objetivos'] += $activacion->getProyecto()->getMarca()->getObjetivo();
        }

        $data_month_plaza = array();
        $plazas = array();
        foreach ($activaciones as $activacion) {
            $month = $activacion->getFecha()->format('M');
            $plaza = $activacion->getCDC()->getPlaza()->getAbreviacion();
            $plazas[] = $plaza;
            @$data_month_plaza[$month][$plaza]['activaciones'] += 1;
        }

        return array('data_month' => $data_month, 'plazas' => $plazas, 'data_month_plaza' => $data_month_plaza);
    }

    protected function getChartData($activaciones) {
        $graph_data = array();

        foreach ($activaciones as $activacion) {
            $semana = $activacion->getFecha()->format('d-m-y');
            @$graph_data[$semana]['activaciones'] += 1;
            @$graph_data[$semana]['ventas'] += $activacion->getCopeo();
            @$graph_data[$semana]['objetivos'] += $activacion->getProyecto()->getMarca()->getObjetivo();
        }

        ksort($graph_data);


        $graph_data_exported = array(
            'activaciones' => $this->getDataKeys('activaciones', $graph_data),
            'objetivos' => $this->getDataKeys('objetivos', $graph_data),
            'ventas' => $this->getDataKeys('ventas', $graph_data),
            'semanas' => array_keys($graph_data)
        );

        return $graph_data_exported;
    }

    protected function getVentas($brand, $usuario, $parameters) {
        $em = $this->getDoctrine()->getManager();
        $ventas = $em->getRepository('ReportBundle:Activacion')->findByVentas($brand, null, $usuario, $parameters);
        return $ventas;
    }

    protected function getActivacionesResume($all_activaciones) {

        $totales = $this->getProyectosActivos($all_activaciones);

        $usadas = array_filter($all_activaciones, function($entry) {
            return in_array($entry->getStatus()->getId(), array(5));
        }
        );

        $proceso = array_filter($all_activaciones, function($entry) {
            return !in_array($entry->getStatus()->getId(), array(5, 3));
        }
        );

        $data = array('activadas' => count($usadas), 'programadas' => count($proceso), 'disponibles' => ($totales - (count($usadas) + count($proceso))));

        return $data;
    }

    protected function getActivacionesTotales($all_activaciones, $activaciones) {
        $data = array();
        $total_key = 'TOT';
        $data[$total_key] = array('Planeadas' => $this->getProyectosActivos($all_activaciones), 'Ejecutadas' => 0, 'Venta' => 0);
        foreach ($activaciones as $activacion) {
            $plaza = $activacion->getCDC()->getPlaza()->getAbreviacion();
            if (!isset($data[$plaza])) {
                $data[$plaza] = array('Planeadas' => 0, 'Ejecutadas' => 0, 'Venta' => 0);
            }
            $data[$plaza]['Planeadas'] = isset($data[$plaza]['Planeadas']) ? $data[$plaza]['Planeadas'] + 1 : 1;
            if ($activacion->getFecha() < new \DateTime()) {
                $data[$total_key]['Ejecutadas'] += 1;
                $data[$plaza]['Ejecutadas'] = isset($data[$plaza]['Ejecutadas']) ? $data[$plaza]['Ejecutadas'] + 1 : 1;
            }
            $data[$plaza]['Venta'] = isset($data[$plaza]['Venta']) ? $data[$plaza]['Venta'] + $activacion->getCopeo() : $activacion->getCopeo();
            $data[$total_key]['Venta'] += $activacion->getCopeo();
        }

        return $data;
    }

    protected function getProyectosActivos($all_activaciones) {
        $em = $this->getDoctrine()->getManager();
        return $em->getRepository('ReportBundle:Proyecto')->findTotalesByActivaciones($all_activaciones)[0]['totales'];
    }

    protected function getDataKeys($key, $array) {
        $rarray = array();
        foreach ($array as $element) {
            $rarray[] = $element[$key];
        }

        return $rarray;
    }

    protected function getColumnasByKey($key, $instrumento) {
        return $instrumento->getReportes()->filter(function($reporte) use ($key) {
                    return $reporte->getKeyReference() == $key;
                });
    }

    protected function getColumnasReport($key, $instrumento) {
        $collection = $this->getColumnasByKey($key, $instrumento);
        //NO ME GUSTA ESTE LOOP ¿COMO SE MEJOARA?
        $columnas = array();
        foreach ($collection as $element) {
            foreach ($element->getColumnas() as $columna) {
                $columnas[] = $columna->getId();
            }
        }

        return $columnas;
    }

    /**
     * @Route("/export.{_format}",  requirements={"_format"="csv|xls|xlsx|html"}, name="export")
     */
    public function exportAction(Request $request) {
        $entities = $request->getSession()->get('activaciones');

        $repository = $this->getDoctrine()->getRepository('ReportBundle:Activacion');
        $qb = $repository->createQueryBuilder('a')
                ->leftJoin('a.filas', 'f')
                ->andWhere('a.id IN (:ids)')
                ->setParameter('ids', $entities)
                ->getQuery()
                ->execute()
        ;

        $format = $request->getRequestFormat();

        return $this->render('report/export.' . $format . '.twig', ['data' => $qb]);
    }

    protected function getShareDataByCDC($brand, $status, $usuario, $parameters) {
        $em = $this->getDoctrine()->getManager();
        $share = array();
        $labels = array();
        $instrumento = $parameters['instrumento'];

        $share_ids = $instrumento ? $this->getColumnasReport('share', $instrumento) : null;

        if ($share_ids) {
            $data = $em->getRepository('ReportBundle:Activacion')->findByShareByCDC($brand, $status, $usuario, array_merge(array('columnas' => $share_ids), $parameters));
            $i = 0;
            foreach ($data as $d) {
                $labels[$d['id']] = $d['nombre'];
                $columna = $d['texto'];
                $share['data'][$columna]['label'] = [$columna];
                $share['data'][$columna]['data'][] = $d['total'];
                $share['data'][$columna]['backgroundColor'] = '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
                $i++;
            }
        }
        $share['labels'] = $labels;


        return $share;
    }

}
