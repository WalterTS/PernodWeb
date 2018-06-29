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
use WE\ReportBundle\Form\UsuarioType;

/**
 * @Route("/administracion/usuario")
 */
class UsuarioController extends MainController {

    /**
     *
     * @Route("/filter_usuario", name="ajax_filter_usr")
     * @Method("GET")
     */
    public function proyectoFilterAction(Request $request) {
        $as = $this->get('tetranz_select2entity.autocomplete_service');
        $result = $as->getAutocompleteResults($request, UsuarioType::class);
        return new JsonResponse($result);
    }

    /**
     *
     * @Route("/", name="usuario_index")
     * @Method("GET")
     */
    public function indexAction(Request $request) {

        $em = $this->getDoctrine()->getManager();

        $usuarios = "";
        if (count($request->query) > 0) {
            $fm = $this->get('padam87_search.filter.manager');
            $searchName = "";
            $searchEmail = "";
            if ($request->get('searchName') != null) {
                $searchName = '*' . $request->get('searchName') . '*';
            }
            if ($request->get('searchEmail') != null) {
                $searchEmail = '*' . $request->get('searchEmail') . '*';
            }
            $filter = new Filter(array(
                'nombre' => $searchName,
                'email' => $searchEmail
                    ), 'ReportBundle:Usuario', 'Usuario');

            if ($request->get('searchFrom') != null && $request->get('searchTo') != null) {
                $usuarios = $fm->createQueryBuilder($filter)
                        ->andWhere('Usuario.updatedAt BETWEEN :searchFrom AND :searchTo')
                        ->setParameter('searchFrom', $request->get('searchFrom'))
                        ->setParameter('searchTo', $request->get('searchTo'))
                        ->orderBy('Usuario.id', 'DESC');
            } else {
                $usuarios = $fm->createQueryBuilder($filter)
                        ->leftJoin('Usuario.agencia', 'a')
                        ->leftJoin('Usuario.region', 'r')
                        ->orderBy('Usuario.id', 'DESC');
            }
        } else {
            $usuarios = $em->getRepository('ReportBundle:Usuario')->findBy(array(), array('id' => 'DESC'));
        }

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $usuarios, $request->query->getInt('page', 1), $this->container->getParameter('max_per_page')
        );


        return $this->render('ReportBundle:admin:usuario/index.html.twig', array(
                    'pagination' => $pagination,
        ));
    }

    /**
     * Creates a new activacion entity.
     *
     * @Route("/new", name="usuario_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request) {

        //MOSTRAR PROYECTOS EN LOS QUE PARTICIPA EL KAM O EL EJECUTIVO DE CUENTA
        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->createUser();
        $form = $this->createForm('WE\ReportBundle\Form\UsuarioType', $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userManager->updateUser($user);
            return $this->redirectToRoute('usuario_show', array('id' => $user->getId()));
        }

        return $this->render('ReportBundle:admin:usuario/new.html.twig', array(
                    'user' => $user,
                    'form' => $form->createView(),
        ));
    }

    /**
     *
     * @Route("/{id}", name="usuario_show")
     * @Method("GET")
     */
    public function showAction(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $usuario = $em->getRepository('ReportBundle:Usuario')->find($request->get('id'));

        return $this->render('ReportBundle:admin:usuario/show.html.twig', array(
                    'usuario' => $usuario
        ));
    }

    /**
     * Displays a form to edit an existing activacion entity.
     *
     * @Route("/{id}/edit", name="usuario_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, \WE\ReportBundle\Entity\Usuario $usuario) {
        $userManager = $this->get('fos_user.user_manager');
        $deleteForm = $this->createDeleteForm($usuario);
        $editForm = $this->createForm('WE\ReportBundle\Form\UsuarioType', $usuario);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $userManager->updateUser($usuario);
            return $this->redirectToRoute('usuario_edit', array('id' => $usuario->getId()));
        }

        return $this->render('ReportBundle:admin:usuario/edit.html.twig', array(
                    'usuario' => $usuario,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a proyecto entity.
     *
     * @Route("/{id}", name="usuario_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, \WE\ReportBundle\Entity\Usuario $usuario) {
        $form = $this->createDeleteForm($usuario);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($usuario);
            $em->flush();
        }

        return $this->redirectToRoute('usuario_index');
    }

    /**
     * Creates a form to delete a proyecto entity.
     *
     * @param Proyecto $proyecto The proyecto entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(\WE\ReportBundle\Entity\Usuario $usuario) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('usuario_delete', array('id' => $usuario->getId())))
                        ->setMethod('DELETE')
                        ->getForm()
        ;
    }

}
