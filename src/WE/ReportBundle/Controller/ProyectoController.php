<?php

namespace WE\ReportBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use WE\ReportBundle\Entity\Proyecto;
use Padam87\SearchBundle\Filter\Filter;
use WE\ReportBundle\Form\ProyectoType;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Activacion controller.
 *
 * @Route("proyecto")
 */
class ProyectoController extends BaseController {

    /**
     *
     * @Route("/filterp", name="ajax_filter_p")
     * @Method("GET")
     */
    public function proyectoFilterAction(Request $request) {
        $as = $this->get('tetranz_select2entity.autocomplete_service');
        $result = $as->getAutocompleteResults($request, ProyectoType::class);
        return new JsonResponse($result);
    }

    /**
     *
     * @Route("/", name="proyecto_index")
     * @Method("GET")
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $proyectos = "";
        if (count($request->query) > 0) {
            $fm = $this->get('padam87_search.filter.manager');
            $searchName = "";
            if ($request->get('searchName') != null) {
                $searchName = '*' . $request->get('searchName') . '*';
            }
            $filter = new Filter(array(
                'nombre' => $searchName
                    ), 'ReportBundle:Proyecto', 'Proyecto');

            if ($request->get('searchFrom') != null && $request->get('searchTo') != null) {
                $proyectos = $fm->createQueryBuilder($filter)
                        ->andWhere('Proyecto.fecha_inicio BETWEEN :searchFrom AND :searchTo')
                        ->setParameter('searchFrom', $request->get('searchFrom'))
                        ->setParameter('searchTo', $request->get('searchTo'))
                        ->orderBy('Proyecto.id','DESC');
            } else {
                $proyectos = $fm->createQueryBuilder($filter)
                        ->leftJoin('Proyecto.cdcs', 'c')
                        ->leftJoin('Proyecto.marca', 'm')
                        ->orderBy('Proyecto.id','DESC');
            }
        } else {
            $proyectos = $em->getRepository('ReportBundle:Proyecto')->findBy(array(), array('id' => 'DESC'));
        }

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $proyectos, $request->query->getInt('page', 1), 10
        );
        return $this->render('proyecto/index.html.twig', array(
                    'pagination' => $pagination,
        ));
    }

    /**
     * Creates a new activacion entity.
     *
     * @Route("/new", name="proyecto_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request) {

        //MOSTRAR PROYECTOS EN LOS QUE PARTICIPA EL KAM O EL EJECUTIVO DE CUENTA
        $em = $this->getDoctrine()->getManager();
        $proyecto = new Proyecto();
        $form = $this->createForm('WE\ReportBundle\Form\ProyectoType', $proyecto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $proyecto->setResponsable($this->getUser());
            $em->persist($proyecto);
            $em->flush();

            $this->container->get('status_generator')->setProyecto($proyecto);

            return $this->redirectToRoute('proyecto_show', array('id' => $proyecto->getId()));
        }

        return $this->render('proyecto/new.html.twig', array(
                    'proyecto' => $proyecto,
                    'form' => $form->createView(),
        ));
    }

    /**
     *
     * @Route("/{id}", name="proyecto_show")
     * @Method("GET")
     */
    public function showAction(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $proyecto = $em->getRepository('ReportBundle:Proyecto')->find($request->get('id'));

        return $this->render('proyecto/show.html.twig', array(
                    'proyecto' => $proyecto
        ));
    }

    /**
     * Displays a form to edit an existing activacion entity.
     *
     * @Route("/{id}/edit", name="proyecto_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Proyecto $proyecto) {
        $deleteForm = $this->createDeleteForm($proyecto);
        $editForm = $this->createForm('WE\ReportBundle\Form\ProyectoType', $proyecto);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('proyecto_edit', array('id' => $proyecto->getId()));
        }

        return $this->render('proyecto/edit.html.twig', array(
                    'proyecto' => $proyecto,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a proyecto entity.
     *
     * @Route("/{id}", name="proyecto_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Proyecto $proyecto) {
        $form = $this->createDeleteForm($proyecto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($proyecto);
            $em->flush();
        }

        return $this->redirectToRoute('proyecto_index');
    }

    /**
     * Creates a form to delete a proyecto entity.
     *
     * @param Proyecto $proyecto The proyecto entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Proyecto $proyecto) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('proyecto_delete', array('id' => $proyecto->getId())))
                        ->setMethod('DELETE')
                        ->getForm()
        ;
    }

}
