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
 * @Route("/administracion/activacion_tipo")
 */
class ActivacionTipoController extends MainController {

    /**
     *
     * @Route("/", name="activaciontipo_index")
     * @Method("GET")
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $activacionestipo = "";
        if (count($request->query) > 0) {
            $fm = $this->get('padam87_search.filter.manager');
            $searchName = "";
            if ($request->get('searchName') != null) {
                $searchName = '*' . $request->get('searchName') . '*';
            }
            $filter = new Filter(array(
                'nombre' => $searchName,
                    ), 'ReportBundle:ActivacionTipo', 'ActivacionTipo');

            if ($request->get('searchFrom') != null && $request->get('searchTo') != null) {
                $activacionestipo = $fm->createQueryBuilder($filter)
                        ->andWhere('ActivacionTipo.updatedAt BETWEEN :searchFrom AND :searchTo')
                        ->setParameter('searchFrom', $request->get('searchFrom'))
                        ->setParameter('searchTo', $request->get('searchTo'))
                        ->orderBy('id', 'DESC');
            } else {
                $activacionestipo = $fm->createQueryBuilder($filter)
                        ->orderBy('id', 'DESC');
            }
        } else {
            $activacionestipo = $em->getRepository('ReportBundle:ActivacionTipo')->findBy(array(), array('id' => 'DESC'));
        }

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $activacionestipo, $request->query->getInt('page', 1), $this->container->getParameter('max_per_page')
        );

        return $this->render('ReportBundle:admin:activacion_tipo/index.html.twig', array(
                    'pagination' => $pagination,
        ));
    }

    /**
     * Creates a new activacion entity.
     *
     * @Route("/new", name="activaciontipo_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request) {

        //MOSTRAR PROYECTOS EN LOS QUE PARTICIPA EL KAM O EL EJECUTIVO DE CUENTA
        $em = $this->getDoctrine()->getManager();
        $activaciontipo = new \WE\ReportBundle\Entity\ActivacionTipo();
        $form = $this->createForm('WE\ReportBundle\Form\ActivacionTipoType', $activaciontipo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($activaciontipo);
            $em->flush();
            return $this->redirectToRoute('activaciontipo_show', array('id' => $activaciontipo->getId()));
        }

        return $this->render('ReportBundle:admin:activacion_tipo/new.html.twig', array(
                    'activaciontipo' => $activaciontipo,
                    'form' => $form->createView(),
        ));
    }

    /**
     *
     * @Route("/{id}", name="activaciontipo_show")
     * @Method("GET")
     */
    public function showAction(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $activaciontipo = $em->getRepository('ReportBundle:ActivacionTipo')->find($request->get('id'));

        return $this->render('ReportBundle:admin:activacion_tipo/show.html.twig', array(
                    'activaciontipo' => $activaciontipo
        ));
    }

    /**
     * Displays a form to edit an existing activacion entity.
     *
     * @Route("/{id}/edit", name="activaciontipo_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, \WE\ReportBundle\Entity\ActivacionTipo $activaciontipo) {
        $em = $this->getDoctrine()->getManager();
        $deleteForm = $this->createDeleteForm($activaciontipo);
        $editForm = $this->createForm('WE\ReportBundle\Form\ActivacionTipoType', $activaciontipo);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em->persist($activaciontipo);
            $em->flush();
            return $this->redirectToRoute('activaciontipo_edit', array('id' => $activaciontipo->getId()));
        }

        return $this->render('ReportBundle:admin:activacion_tipo/edit.html.twig', array(
                    'activaciontipo' => $activaciontipo,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a proyecto entity.
     *
     * @Route("/{id}", name="activaciontipo_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, \WE\ReportBundle\Entity\ActivacionTipo $activaciontipo) {
        $form = $this->createDeleteForm($activaciontipo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($activaciontipo);
            $em->flush();
        }

        return $this->redirectToRoute('activaciontipo_index');
    }

    /**
     * Creates a form to delete a proyecto entity.
     *
     * @param Proyecto $proyecto The proyecto entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(\WE\ReportBundle\Entity\ActivacionTipo $activaciontipo) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('activaciontipo_delete', array('id' => $activaciontipo->getId())))
                        ->setMethod('DELETE')
                        ->getForm()
        ;
    }

}
