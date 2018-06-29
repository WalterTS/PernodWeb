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
 * @Route("/administracion/plaza")
 */
class PlazaController extends MainController {

    /**
     *
     * @Route("/", name="plaza_index")
     * @Method("GET")
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $plazas = "";
        if (count($request->query) > 0) {
            $fm = $this->get('padam87_search.filter.manager');
            $searchName = "";
            $searchRegion = "";
            if ($request->get('searchName') != null) {
                $searchName = '*' . $request->get('searchName') . '*';
            }
            if ($request->get('searchEmail') != null) {
                $searchRegion = '*' . $request->get('searchRegion') . '*';
            }
            $filter = new Filter(array(
                'nombre' => $searchName,
                'region' => $searchRegion
                    ), 'ReportBundle:Plaza', 'Plaza');

            if ($request->get('searchFrom') != null && $request->get('searchTo') != null) {
                $plazas = $fm->createQueryBuilder($filter)
                        ->andWhere('Plaza.updatedAt BETWEEN :searchFrom AND :searchTo')
                        ->setParameter('searchFrom', $request->get('searchFrom'))
                        ->setParameter('searchTo', $request->get('searchTo'))
                        ->orderBy('Plaza.id', 'DESC');
            } else {
                $plazas = $fm->createQueryBuilder($filter)
                        ->leftJoin('Plaza.region', 'r')
                        ->orderBy('Plaza.id', 'DESC');
            }
        } else {
            $plazas = $em->getRepository('ReportBundle:Plaza')->findBy(array(), array('id' => 'DESC'));
        }

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $plazas, $request->query->getInt('page', 1), $this->container->getParameter('max_per_page')
        );

        return $this->render('ReportBundle:admin:plaza/index.html.twig', array(
                    'pagination' => $pagination,
        ));
    }

    /**
     * Creates a new activacion entity.
     *
     * @Route("/new", name="plaza_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request) {

        //MOSTRAR PROYECTOS EN LOS QUE PARTICIPA EL KAM O EL EJECUTIVO DE CUENTA
        $em = $this->getDoctrine()->getManager();
        $plaza = new \WE\ReportBundle\Entity\Plaza();
        $form = $this->createForm('WE\ReportBundle\Form\PlazaType', $plaza);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($plaza);
            $em->flush();
            return $this->redirectToRoute('plaza_show', array('id' => $plaza->getId()));
        }

        return $this->render('ReportBundle:admin:plaza/new.html.twig', array(
                    'plaza' => $plaza,
                    'form' => $form->createView(),
        ));
    }

    /**
     *
     * @Route("/{id}", name="plaza_show")
     * @Method("GET")
     */
    public function showAction(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $plaza = $em->getRepository('ReportBundle:Plaza')->find($request->get('id'));

        return $this->render('ReportBundle:admin:plaza/show.html.twig', array(
                    'plaza' => $plaza
        ));
    }

    /**
     * Displays a form to edit an existing activacion entity.
     *
     * @Route("/{id}/edit", name="plaza_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, \WE\ReportBundle\Entity\Plaza $plaza) {
          $em = $this->getDoctrine()->getManager();
        $deleteForm = $this->createDeleteForm($plaza);
        $editForm = $this->createForm('WE\ReportBundle\Form\PlazaType', $plaza);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em->persist($plaza);
            $em->flush();
            return $this->redirectToRoute('plaza_edit', array('id' => $plaza->getId()));
        }

        return $this->render('ReportBundle:admin:plaza/edit.html.twig', array(
                    'plaza' => $plaza,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a proyecto entity.
     *
     * @Route("/{id}", name="plaza_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, \WE\ReportBundle\Entity\Plaza $plaza) {
        $form = $this->createDeleteForm($plaza);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($plaza);
            $em->flush();
        }

        return $this->redirectToRoute('plaza_index');
    }

    /**
     * Creates a form to delete a proyecto entity.
     *
     * @param Proyecto $proyecto The proyecto entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(\WE\ReportBundle\Entity\Plaza $plaza) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('plaza_delete', array('id' => $plaza->getId())))
                        ->setMethod('DELETE')
                        ->getForm()
        ;
    }

}
