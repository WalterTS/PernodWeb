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
use Symfony\Component\HttpFoundation\JsonResponse;
use WE\ReportBundle\Form\CDCType;

/**
 * @Route("/administracion/cdc")
 */
class CdcController extends MainController {

    /**
     *
     * @Route("/filter_cdc", name="ajax_filter_cdc")
     * @Method("GET")
     */
    public function proyectoFilterAction(Request $request) {
        $as = $this->get('tetranz_select2entity.autocomplete_service');
        $result = $as->getAutocompleteResults($request, CDCType::class);
        return new JsonResponse($result);
    }

    /**
     *
     * @Route("/", name="cdc_index")
     */ 
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $cdcs = "";
        if (count($request->query) > 0) {
            $fm = $this->get('padam87_search.filter.manager');
            $searchName = "";
            if ($request->get('searchName') != null) {
                $searchName = '*' . $request->get('searchName') . '*';
            }
            $filter = new Filter(array(
                'nombre' => $searchName,
                    ), 'ReportBundle:Cdc', 'Cdc');

            if ($request->get('searchFrom') != null && $request->get('searchTo') != null) {
                $cdcs = $fm->createQueryBuilder($filter)
                        ->andWhere('Cdc.updatedAt BETWEEN :searchFrom AND :searchTo')
                        ->setParameter('searchFrom', $request->get('searchFrom'))
                        ->setParameter('searchTo', $request->get('searchTo'))
                        ->orderBy('Cdc.id', 'DESC');
            } else {
                $cdcs = $fm->createQueryBuilder($filter)
                        ->leftJoin('Cdc.tipo', 't')
                        ->leftJoin('Cdc.plaza', 'p')
                        ->orderBy('Cdc.id', 'DESC');
            }
        } else {
            $cdcs = $em->getRepository('ReportBundle:Cdc')->findBy(array(), array('id' => 'DESC'));
        }

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $cdcs, $request->query->getInt('page', 1), $this->container->getParameter('max_per_page')
        );


        return $this->render('ReportBundle:admin:cdc/index.html.twig', array(
                    'pagination' => $pagination,
        ));
    }

    /**
     *
     * @Route("/new", name="cdc_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $entity = new \WE\ReportBundle\Entity\CDC();
        $form = $this->createForm('WE\ReportBundle\Form\CDCType', $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($entity);
            $em->flush();
            return $this->redirectToRoute('cdc_show', array('id' => $entity->getId()));
        }

        return $this->render('ReportBundle:admin:cdc/new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     *
     * @Route("/{id}", name="cdc_show")
     * @Method("GET")
     */
    public function showAction(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('ReportBundle:CDC')->find($request->get('id'));

        return $this->render('ReportBundle:admin:cdc/show.html.twig', array(
                    'entity' => $entity
        ));
    }

    /**
     * Displays a form to edit an existing activacion entity.
     *
     * @Route("/{id}/edit", name="cdc_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, \WE\ReportBundle\Entity\CDC $entity) {
        $em = $this->getDoctrine()->getManager();
        $deleteForm = $this->createDeleteForm($entity);
        $editForm = $this->createForm('WE\ReportBundle\Form\CDCType', $entity);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em->persist($entity);
            $em->flush();
            return $this->redirectToRoute('cdc_show', array('id' => $entity->getId()));
        }

        return $this->render('ReportBundle:admin:cdc/edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a proyecto entity.
     *
     * @Route("/{id}", name="cdc_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, \WE\ReportBundle\Entity\CDC $entity) {
        $form = $this->createDeleteForm($entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($entity);
            $em->flush();
        }

        return $this->redirectToRoute('cdc_index');
    }

    /**
     * Creates a form to delete a proyecto entity.
     *
     * @param Proyecto $proyecto The proyecto entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(\WE\ReportBundle\Entity\CDC $entity) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('usuario_delete', array('id' => $entity->getId())))
                        ->setMethod('DELETE')
                        ->getForm()
        ;
    }

}
