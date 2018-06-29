<?php

namespace WE\ReportBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use WE\ReportBundle\Entity\Valor;
use WE\ReportBundle\Entity\Activacion;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

/**
 * @Route("/capture")
 */
class CaptureController extends BaseController {
    
    /**
     *
     * @Route("/list", name="activaciones_list")
     * @Method("GET")
     */
    public function listAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $brand = null;

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $this->findActivacionesByRank($em, $brand), $request->query->getInt('page', 1), 10
        );

        return $this->render('ReportBundle:activaciones:list.html.twig', array(
                    'pagination' => $pagination,
        ));
    }

    /**
     * @Route("/update/{app_id}",name="update_app")
     * @Template("ReportBundle:activaciones:capture.html.twig")
     */
    public function updateAction(Request $request, $app_id) {
        $em = $this->getDoctrine()->getManager();
        $app = $em->getRepository('ReportBundle:Activacion')->find($app_id);
        $instrumento = $app->getProyecto()->getMarca()->getInstrumentos()->last();

        $form = $this->createDynamicForm($app)->getForm();

        if ($app->getStatus()->getId() == 6) {

            if ($app->getImages()->count() == 0) {
                $image = new \WE\ReportBundle\Entity\Gallery();
                $image->setNombre('Imágen de llegada');
                $app->addImage($image);
            }

            $form_activacion = $this->createForm('WE\ReportBundle\Form\ActivacionCheckinType', $app);
        } else {
            $form_activacion = $this->createForm('WE\ReportBundle\Form\ActivacionSupervisorType', $app);
        }
        $form_activacion->handleRequest($request);

        if ($form_activacion->isSubmitted() && $form_activacion->isValid()) {

            if ($app->getStatus()->getId() == 6) {
                $status = $em->getRepository('ReportBundle:ActivacionStatus')->find(9);
                $app->setStatus($status);

                $this->container->get('status_generator')->setStatus($app);
            }

            if ($app->getStatus()->getId() == 13) {
                $status = $em->getRepository('ReportBundle:ActivacionStatus')->find(7);
                $app->setStatus($status);
            }

            $em->flush();
            return $this->redirect($this->generateUrl('capture_app', array('app_id' => $app_id)));
        }

        return array('activacion' => $app, 'instrumento' => $instrumento, 'form' => $form->createView(), 'form_activacion' => $form_activacion->createView());
    }

    protected function createDynamicForm($app) {
        $form = $this->container->get('form.factory')->createNamedBuilder('activacion_form');

        $instrumento = $app->getProyecto()->getMarca()->getInstrumentos()->last();

        $valores = $app->getFilas() && $app->getFilas()->last() && $app->getFilas()->last()->getValores() ? $app->getFilas()->last()->getValores() : null;

        foreach ($instrumento->getSecciones() as $seccion) {
            foreach ($seccion->getColumnas() as $columna) {
                $type = $this->get('cocur_slugify')->slugify($columna->getTipo()->getTipoColumna());

                switch ($type) {
                    case "booleano":
                        $expanded = true;
                        $multiple = false;
                        $type = "boolean";
                        break;
                    case "opcion-multiple":
                        $expanded = true;
                        $multiple = true;
                        $type = "multiple";
                        break;
                    case "opciones":
                        $expanded = false;
                        $multiple = false;
                        $type = "multiple";
                        break;
                    case "fecha":
                        $expanded = false;
                        $multiple = false;
                        $type = "date";
                        break;
                    case "precio":
                    case "entero":
                    case "porcentaje":
                        $expanded = false;
                        $multiple = false;
                        $type = "number";
                        break;
                    case "texto":
                        $expanded = true;
                        $multiple = false;
                        $type = "text";
                        break;
                    default:
                        $expanded = false;
                        $multiple = false;
                        $type = "text";
                        break;
                }
                $methodName = 'add' . ucwords($type);
                call_user_func_array(array($this, $methodName), array(array('valor' => $this->getValor($valores, $columna), 'expanded' => $expanded, 'multiple' => $multiple, 'columna' => $columna, 'form' => $form)));
            }
        }

        $form->add('save', \Symfony\Component\Form\Extension\Core\Type\SubmitType::class, array('label' => 'GUARDAR'));

        return $form;
    }

    protected function getValor($valores, $columna) {
        return $valores ? $valores->filter(
                        function($entry) use ($columna) {
                    return $entry->getColumna() == $columna;
                }
                ) : null;
    }

    /**
     * @Route("/{app_id}",name="capture_app")
     * @Template("ReportBundle:activaciones:capture.html.twig")
     */
    public function captureAction(Request $request, $app_id) {
        $em = $this->getDoctrine()->getManager();
        $app = $em->getRepository('ReportBundle:Activacion')->find($app_id);

        $instrumento = $app->getProyecto()->getMarca()->getInstrumentos()->last();

        $comentario = new \WE\ReportBundle\Entity\ActivacionComentario();
        $comentario->setUserFrom($this->getUser());
        $comentario->setActivacion($app);
        $comentario_form = $this->createForm('WE\ReportBundle\Form\ActivacionComentarioSupervisorType', $comentario);

        if ($app->getStatus()->getId() == 6) {
            if ($app->getImages()->count() == 0) {
                $image = new \WE\ReportBundle\Entity\Gallery();
                $image->setNombre('Imágen de llegada');
                $app->addImage($image);
            }

            $form_activacion = $this->createForm('WE\ReportBundle\Form\ActivacionCheckinType', $app);
        } else {
            $form_activacion = $this->createForm('WE\ReportBundle\Form\ActivacionSupervisorType', $app);
        }

        $form_activacion->handleRequest($request);

        //VALIDAR SI TIENE ALGUN OTRO STATUS QUE NO SEA RECHAZADO
        if (($app->getFilas()->count() && $app->getStatus()->getId() != 13 && $app->getStatus()->getId() != 7) && ($app->getStatus()->getId() == 14 && !$this->get('security.authorization_checker')->isGranted('ROLE_USER_PRODUCTOR'))) {
            return $this->redirectToRoute('activacion_show', array('id' => $app->getId()));
        }

        $form = $this->createDynamicForm($app)->getForm();

        if ($request->isMethod('POST')) {
            $data = $form->handleRequest($request);
            if ($form->isValid()) {
                if ($app->getFilas()->count()) {
                    foreach ($app->getFilas()->last()->getValores() as $valor) {
                        $em->remove($valor);
                    }
                }

                $fila = new \WE\ReportBundle\Entity\Fila();
                $fila->setActivacion($app);
                foreach ($data as $value) {
                    $columna = $em->getRepository('ReportBundle:Columna')->find($value->getName());
                    //FALTA LOOPEAR TODOS  LOS VALORES
                    if ($columna && $columna->getId()) {
                        if (is_array($value->getData())) {
                            foreach ($value->getData() as $individual) {
                                $this->addValor($em, $columna, $fila, $individual);
                            }
                        } else {
                            $this->addValor($em, $columna, $fila, $value->getData());
                        }
                    }
                }

                $em->persist($fila);

                if ($this->get('security.authorization_checker')->isGranted('ROLE_USER_PRODUCTOR')) {
                    $status = $em->getRepository('ReportBundle:ActivacionStatus')->find(10);
                } else {
                    $status = $em->getRepository('ReportBundle:ActivacionStatus')->find(4);
                }
                $app->setStatus($status);

                $em->persist($app);
                $em->flush();
                $this->container->get('status_generator')->setStatus($app);
                return $this->redirectToRoute('activacion_show', array('id' => $app->getId()));
            }
            $em->flush();
        }


        return array('activacion' => $app,'comentario_form' => $comentario_form->createView(), 'instrumento' => $instrumento, 'form' => $form->createView(), 'form_activacion' => $form_activacion->createView());
    }

    protected function addDate($params) {
        extract($params);
        $form->add($columna->getId(), \Symfony\Component\Form\Extension\Core\Type\DateTimeType::class, array(
            'label' => $columna->getTexto()
        ));
        return $form;
    }

    protected function addBoolean($params) {
        extract($params);

        $valor = $valor && $valor->last() ? (bool) $valor->last()->getValor() : null;


        $form->add($columna->getId(), ChoiceType::class, array(
            'data' => $valor,
            'label' => $columna->getTexto(),
            'choices' => array(
                '-- Selecciona --' => null,
                'Si' => true,
                'No' => false,
            ),
        ));

        return $form;
    }

    protected function addText($params) {
        extract($params);
        $valor = $valor && $valor->last() ? $valor->last()->getValor() : null;


        $type = $expanded ? \Symfony\Component\Form\Extension\Core\Type\TextareaType::class : \Symfony\Component\Form\Extension\Core\Type\TextType::class;

        $form->add($columna->getId(), $type, array(
            'data' => $valor,
            'label' => $columna->getTexto()
        ));
        return $form;
    }

    protected function addNumber($params) {
        extract($params);
        $valor = $valor && $valor->last() ? $valor->last()->getValor() : 0;


        $form->add($columna->getId(), \Symfony\Component\Form\Extension\Core\Type\NumberType::class, array(
            'data' => $valor,
            'label' => $columna->getTexto(),
        ));
        return $form;
    }

    protected function addMultiple($params) {
        extract($params);
        $valor = $valor && $valor->last() ? $valor->last()->getItem()->getId() : null;
        $options = array();
        foreach ($columna->getItems() as $item) {
            $options[$item->getTexto()] = $item->getId();
        }


        $form->add($columna->getId(), \Symfony\Component\Form\Extension\Core\Type\ChoiceType::class, array(
            'placeholder' => '-- Selecciona --',
            'data' => $valor,
            'label' => $columna->getTexto(),
            'expanded' => $expanded,
            'multiple' => $multiple,
            'choices' => $options,
        ));

        return $form;
    }

}
