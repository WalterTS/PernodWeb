<?php

namespace WE\ReportBundle\Controller;

use WE\ReportBundle\Entity\Activacion;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use WE\ReportBundle\Entity\UsuarioActivacion;
use Padam87\SearchBundle\Filter\Filter;

/**
 * Activacion controller.
 *
 * @Route("activacion")
 */
class ActivacionController extends BaseController {

    /**
     * Lists all activacion entities.
     *
     * @Route("/", name="activacion_index")
     * @Method("GET")
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $activaciones = "";
        if (count($request->query) > 0) {
            $fm = $this->get('padam87_search.filter.manager');
            $searchName = "";
            if ($request->get('searchName') != null) {
                $searchName = '*' . $request->get('searchName') . '*';
            }
            $filter = new Filter(array(
                'nombre' => $searchName,
                    ), 'ReportBundle:Activacion', 'Activacion');
            $searchTo = $request->get('searchTo');

            if ($searchTo == null) {
                $searchTo = "2200-01-01";
            }
            if ($request->get('searchFrom') != null) {
                $activaciones = $fm->createQueryBuilder($filter)
                        ->andWhere('Activacion.fecha BETWEEN :searchFrom AND :searchTo')
                        ->setParameter('searchFrom', $request->get('searchFrom'))
                        ->setParameter('searchTo', $searchTo)
                        ->andWhere('Activacion.id = :ids')
                        ->setParameter('ids', $this->findActivacionesByRank($em, null, null));
            } else {
                $activaciones = $fm->createQueryBuilder($filter)
                        ->leftJoin('Activacion.cdc', 'c')
                        ->leftJoin('Activacion.status', 's')
                        ->andWhere('Activacion.id = :ids')
                        ->setParameter('ids', $this->findActivacionesByRank($em, null, null));
            }
        } else {
            $activaciones = $this->findActivacionesByRank($em, null, null);
        }

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $activaciones, $request->query->getInt('page', 1), $this->container->getParameter('max_per_page')
        );
        return $this->render('activacion/index.html.twig', array(
                    'pagination' => $pagination,
        ));
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

    protected function findUserByRole($role, $agencia = null) {
        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository('ReportBundle:Usuario')->findByRoleQuery($role);
        if ($agencia) {
            $query->andWhere('u.agencia = :agencia')
                    ->setParameter('agencia', $agencia);
        }
        return $query->getQuery()->getResult();
    }

    /**
     * Creates a new activacion entity.
     *
     * @Route("/new", name="activacion_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request) {

        //MOSTRAR PROYECTOS EN LOS QUE PARTICIPA EL KAM O EL EJECUTIVO DE CUENTA

        $em = $this->getDoctrine()->getManager();
        //SI NO ES KAM NO TIENE POR QUE ESTAR AQUI

        $activacion = new Activacion();

        //ASIGNO LA ACTIVACION AL USUARIO ACTIVO YA QUE ES UN KAM
        //SE ASIGNO PREVIAMENTE EL USUARIO DE GERENTE YA QUE EL SERA EL RESPONSBALE

        $form = $this->createForm('WE\ReportBundle\Form\ActivacionType', $activacion);


        //ESTE CENTRO DE CONSUMO DEBE DE SER VARIABLE EN BASE AL PROYECTO

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $activacion->setTotal(0);
            $activacion->setBotellas(0);
            $activacion->setCopeo(0);
            $status = $em->getRepository('ReportBundle:ActivacionStatus')->find(1);

            //SE AGREGAN USUARIOS BASICOS
            //SE AGREGA EL KAM ES EL USUARIO LOGEADO
            //QUE TIPO DE KAM ES?? IMPORTANTE AJUSTARLO
            $activacion->addUsuario($this->addUserToActivacion('KAM', $this->getUser()));
            $activacion->addUsuario($this->addUserToActivacion('Gerente de marca', $activacion->getProyecto()->getResponsable()));

            //BUSCAR USUARIO DE AGENCIA

            foreach ($this->fetchUsuarios(array('ROLE_USER_CUENTA'), $activacion->getProyecto()->getAgencia()) as $usuario) {
                $activacion->addUsuario($usuario);
            }


            $activacion->setStatus($status);
            $em->persist($activacion);
            $em->flush();

            $this->container->get('status_generator')->setStatus($activacion);

            $this->container->get('proyect_validator')->setWarning($activacion);

            return $this->redirectToRoute('activacion_show', array('id' => $activacion->getId()));
        }

        return $this->render('activacion/new.html.twig', array(
                    'activacion' => $activacion,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a activacion entity.
     *
     * @Route("/producers/{id}", name="activacion_producers")
     * @Method("POST")
     */
    public function producersAction(Activacion $activacion, Request $request) {
        $form = $this->createForm('WE\ReportBundle\Form\ActivacionEjecutivoCuentaType', $activacion);
        $form->handleRequest($request);
        //ENVIAR POR AJAX

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $status = $em->getRepository('ReportBundle:ActivacionStatus')->find(8);
            $activacion->setStatus($status);
            foreach ($form->get('productores')->getData() as $productor) {
                $existe = false;

                foreach ($activacion->getUsuarios() as $usuario) {
                    if ($usuario->getUsuario() == $productor->getUsuario())
                        $existe = true;
                }

                if (!$existe) {
                    $activacion->addUsuario($productor);
                }
            }

            $this->container->get('status_generator')->setProducers($activacion);
            $em->flush();
            return $this->redirectToRoute('activacion_show', array('id' => $activacion->getId()));
        }
    }

    /**
     * Finds and displays a activacion entity.
     *
     * @Route("/supervisors/{id}", name="activacion_supervisors")
     * @Method({"GET", "POST"})
     */
    public function supervisorsAction(Activacion $activacion, Request $request) {

        $form = $this->createForm('WE\ReportBundle\Form\ActivacionProductorType', $activacion);
        //ENVIAR POR AJAX

        $supervisores = $this->findUsers('Supervisor', $activacion);

        if ($supervisores->count()) {
            $form->get('supervisores')->setData($supervisores);
        }

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            if (!$supervisores->count()) {
                $status = $em->getRepository('ReportBundle:ActivacionStatus')->find(6);
                $activacion->setStatus($status);
            }

            foreach ($supervisores as $sup) {
                $activacion->removeUsuario($sup);
                $em->remove($sup);
                $em->flush();
            }

            foreach ($form->get('supervisores')->getData() as $supervisor) {
                $activacion->addUsuario($supervisor);
            }


            $this->container->get('status_generator')->setSupervisors($activacion);
            $em->persist($activacion);
            $em->flush();
            return $this->redirectToRoute('activacion_show', array('id' => $activacion->getId()));
        }

        return $this->render('activacion/supervisors.html.twig', array(
                    'activacion' => $activacion,
                    'form' => $form->createView(),
        ));
    }

    /**
     *
     * @Route("/{id}", name="activacion_show")
     * @Method("GET")
     */
    public function showAction(Activacion $activacion, Request $request) {

        $type = $this->get('security.authorization_checker')->isGranted('ROLE_USER_CUENTA') ? 'WE\ReportBundle\Form\ActivacionEjecutivoCuentaType' : 'WE\ReportBundle\Form\ActivacionProductorType';

        $form = $this->createForm($type, $activacion);

        return $this->render('activacion/show.html.twig', array(
                    'activacion' => $activacion,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing activacion entity.
     *
     * @Route("/{id}/edit", name="activacion_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Activacion $activacion) {
        $deleteForm = $this->createDeleteForm($activacion);
        $editForm = $this->createForm('WE\ReportBundle\Form\ActivacionType', $activacion);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('activacion_edit', array('id' => $activacion->getId()));
        }

        return $this->render('activacion/edit.html.twig', array(
                    'activacion' => $activacion,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a activacion entity.
     *
     * @Route("/{id}", name="activacion_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Activacion $activacion) {
        $form = $this->createDeleteForm($activacion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($activacion);
            $em->flush();
        }

        return $this->redirectToRoute('activacion_index');
    }

    /**
     * Creates a form to delete a activacion entity.
     *
     * @param Activacion $activacion The activacion entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Activacion $activacion) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('activacion_delete', array('id' => $activacion->getId())))
                        ->setMethod('DELETE')
                        ->getForm()
        ;
    }

}
