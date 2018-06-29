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

/**
 * @Route("/administracion/cdc_tipo")
 */
class CdcTipoController extends MainController {

    /**
     *
     * @Route("/", name="cdctipo_index")
     * @Method("GET")
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $cdcstipo = "";
        if (count($request->query) > 0) {
            $fm = $this->get('padam87_search.filter.manager');
            $searchName = "";
            if ($request->get('searchName') != null) {
                $searchName = '*' . $request->get('searchName') . '*';
            }
            $filter = new Filter(array(
                'nombre' => $searchName,
                    ), 'ReportBundle:CdcType', 'CdcType');

            if ($request->get('searchFrom') != null && $request->get('searchTo') != null) {
                $cdcstipo = $fm->createQueryBuilder($filter)
                        ->andWhere('CdcType.updatedAt BETWEEN :searchFrom AND :searchTo')
                        ->setParameter('searchFrom', $request->get('searchFrom'))
                        ->setParameter('searchTo', $request->get('searchTo'))
                        ->orderBy('id', 'DESC');
            } else {
                $cdcstipo = $fm->createQueryBuilder($filter)
                        ->orderBy('id', 'DESC');
            }
        } else {
            $cdcstipo = $em->getRepository('ReportBundle:CdcType')->findBy(array(), array('id' => 'DESC'));
        }

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $cdcstipo, $request->query->getInt('page', 1), $this->container->getParameter('max_per_page')
        );

        return $this->render('ReportBundle:admin:cdc_tipo/index.html.twig', array(
                    'pagination' => $pagination,
        ));
    }

    /**
     * Creates a new activacion entity.
     *
     * @Route("/new", name="cdctipo_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request) {

        //MOSTRAR PROYECTOS EN LOS QUE PARTICIPA EL KAM O EL EJECUTIVO DE CUENTA
        $em = $this->getDoctrine()->getManager();
        $cdctipo = new \WE\ReportBundle\Entity\CDCType();
        $form = $this->createForm('WE\ReportBundle\Form\CDCTypeType', $cdctipo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($cdctipo);
            $em->flush();
            return $this->redirectToRoute('cdctipo_show', array('id' => $cdctipo->getId()));
        }

        return $this->render('ReportBundle:admin:cdc_tipo/new.html.twig', array(
                    'cdctipo' => $cdctipo,
                    'form' => $form->createView(),
        ));
    }

    /**
     *
     * @Route("/{id}", name="cdctipo_show")
     * @Method("GET")
     */
    public function showAction(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $cdctipo = $em->getRepository('ReportBundle:CDCType')->find($request->get('id'));

        return $this->render('ReportBundle:admin:cdc_tipo/show.html.twig', array(
                    'cdctipo' => $cdctipo
        ));
    }

    /**
     * Displays a form to edit an existing activacion entity.
     *
     * @Route("/{id}/edit", name="cdctipo_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, \WE\ReportBundle\Entity\CDCType $cdctipo) {
         $em = $this->getDoctrine()->getManager();
        $deleteForm = $this->createDeleteForm($cdctipo);
        $editForm = $this->createForm('WE\ReportBundle\Form\CDCTypeType', $cdctipo);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em->persist($cdctipo);
            $em->flush();
            return $this->redirectToRoute('cdctipo_edit', array('id' => $cdctipo->getId()));
        }

        return $this->render('ReportBundle:admin:cdc_tipo/edit.html.twig', array(
                    'cdctipo' => $cdctipo,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a proyecto entity.
     *
     * @Route("/{id}", name="cdctipo_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, \WE\ReportBundle\Entity\CDCType $cdctipo) {
        $form = $this->createDeleteForm($cdctipo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($cdctipo);
            $em->flush();
        }

        return $this->redirectToRoute('cdc_tipo_index');
    }

    /**
     * Creates a form to delete a proyecto entity.
     *
     * @param Proyecto $proyecto The proyecto entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(\WE\ReportBundle\Entity\CDCType $cdctipo) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('cdctipo_delete', array('id' => $cdctipo->getId())))
                        ->setMethod('DELETE')
                        ->getForm()
        ;
    }

}
