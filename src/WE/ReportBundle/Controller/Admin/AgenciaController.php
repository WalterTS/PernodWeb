<?php

namespace WE\ReportBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use WE\ReportBundle\Entity\Valor;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use WE\ReportBundle\Controller\MainController;
use Padam87\SearchBundle\Filter\Filter;
use WE\ReportBundle\Form\AgenciaType;
use Symfony\Component\HttpFoundation\JsonResponse;
/**
 * @Route("/administracion/agencia")
 */
class AgenciaController extends MainController {

    /**
     *
     * @Route("/filter_agencia", name="ajax_filter_ag")
     * @Method("GET")
     */
    public function agenciaFilterAction(Request $request) {
        $as = $this->get('tetranz_select2entity.autocomplete_service');
        $result = $as->getAutocompleteResults($request, AgenciaType::class);
        return new JsonResponse($result);
    }

 /**
     *
     * @Route("/", name="agencia_index")
     * @Method("GET")
     */ 
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

           $agencias = "";
        if (count($request->query) > 0) {
            $fm = $this->get('padam87_search.filter.manager');
            $searchName= "";
            if ($request->get('searchName') != null) {
               $searchName = '*'.$request->get('searchName').'*'; 
            }
            $filter = new Filter(array(
                'nombre' => $searchName,
            ), 'ReportBundle:Agencia', 'Agencia');
            
            if ($request->get('searchFrom') != null && $request->get('searchTo') != null) {
                $agencias = $fm->createQueryBuilder($filter)
                ->andWhere('Agencia.updatedAt BETWEEN :searchFrom AND :searchTo')
                ->setParameter('searchFrom', $request->get('searchFrom'))
                ->setParameter('searchTo', $request->get('searchTo'));
            }else{
                $agencias = $fm->createQueryBuilder($filter);
            }
            
       }else{
        $agencias = $em->getRepository('ReportBundle:Agencia')->findAll();
       }

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $agencias, 
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('ReportBundle:admin:agencia/index.html.twig', array(
                    'pagination' => $pagination,
        ));
    }

     /**
     * Creates a new activacion entity.
     *
     * @Route("/new", name="agencia_new")
     * @Method({"GET", "POST"})
     */
       public function newAction(Request $request) {

        //MOSTRAR PROYECTOS EN LOS QUE PARTICIPA EL KAM O EL EJECUTIVO DE CUENTA
       $em = $this->getDoctrine()->getManager();
        $agencia = new \WE\ReportBundle\Entity\Agencia;
        $form = $this->createForm('WE\ReportBundle\Form\AgenciaType', $agencia);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($agencia);
            $em->flush(); 
            return $this->redirectToRoute('agencia_show', array('id' => $agencia->getId()));
        }

        return $this->render('ReportBundle:admin:agencia/new.html.twig', array(
                    'agencia' => $agencia,
                    'form' => $form->createView(),
        ));
    }

   /**
     *
     * @Route("/{id}", name="agencia_show")
     * @Method("GET")
     */
    public function showAction(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $agencia = $em->getRepository('ReportBundle:Agencia')->find($request->get('id'));

        return $this->render('ReportBundle:admin:agencia/show.html.twig', array(
                    'agencia' => $agencia
        ));
    }

    /**
     * Displays a form to edit an existing activacion entity.
     *
     * @Route("/{id}/edit", name="agencia_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, \WE\ReportBundle\Entity\Agencia $agencia) {
        $em = $this->getDoctrine()->getManager();
        $deleteForm = $this->createDeleteForm($agencia);
        $editForm = $this->createForm('WE\ReportBundle\Form\AgenciaType', $agencia);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em->persist($agencia);
            $em->flush();
            return $this->redirectToRoute('agencia_edit', array('id' => $agencia->getId()));
        }

        return $this->render('ReportBundle:admin:plaza/edit.html.twig', array(
                    'agencia' => $agencia,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a proyecto entity.
     *
     * @Route("/{id}", name="agencia_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, \WE\ReportBundle\Entity\Agencia $agencia) {
        $form = $this->createDeleteForm($agencia);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($agencia);
            $em->flush();
        }

        return $this->redirectToRoute('agencia_index');
    }

    /**
     * Creates a form to delete a proyecto entity.
     *
     * @param Proyecto $proyecto The proyecto entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(\WE\ReportBundle\Entity\Agencia $agencia) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('agencia_delete', array('id' => $agencia->getId())))
                        ->setMethod('DELETE')
                        ->getForm()
        ;
    }

}