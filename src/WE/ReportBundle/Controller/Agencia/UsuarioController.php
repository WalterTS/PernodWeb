<?php

namespace WE\ReportBundle\Controller\Agencia;

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
 * @Route("/agencia/usuario")
 */
class UsuarioController extends MainController {

    /**
     *
     * @Route("/", name="agencia_usuario_index")
     * @Method("GET")
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $agencia = $this->getUser()->getAgencia();

        if (!$agencia) {
            return $this->redirectToRoute('homepage');
        }

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

            if ($searchName != null || $searchEmail != null) {
                $agencia = $fm->createQueryBuilder($filter)
                        ->andWhere('Usuario.agencia = :agencia')
                        ->setParameter('agencia', $this->getUser()->getAgencia());
            }
        } else {
            $agencia = $em->getRepository('ReportBundle:Usuario')->findBy(array('agencia' => $this->getUser()->getAgencia()));
        }

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $agencia, $request->query->getInt('page', 1), 10
        );

        return $this->render('ReportBundle:Agencia:usuario/index.html.twig', array(
                    'pagination' => $pagination,
        ));
    }

    /**
     * Creates a new activacion entity.
     *
     * @Route("/new", name="agencia_usuario_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request) {

        //MOSTRAR PROYECTOS EN LOS QUE PARTICIPA EL KAM O EL EJECUTIVO DE CUENTA
        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->createUser();
        $form = $this->createForm('WE\ReportBundle\Form\UsuarioAgenciaType', $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setAgencia($this->getUser()->getAgencia());
            $userManager->updateUser($user);
            return $this->redirectToRoute('agencia_usuario_show', array('id' => $user->getId()));
        }

        return $this->render('ReportBundle:Agencia:usuario/new.html.twig', array(
                    'user' => $user,
                    'form' => $form->createView(),
        ));
    }

    /**
     *
     * @Route("/{id}", name="agencia_usuario_show")
     * @Method("GET")
     */
    public function showAction(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $usuario = $em->getRepository('ReportBundle:Usuario')->find($request->get('id'));

        return $this->render('ReportBundle:Agencia:usuario/show.html.twig', array(
                    'usuario' => $usuario
        ));
    }

    /**
     * Displays a form to edit an existing activacion entity.
     *
     * @Route("/{id}/edit", name="agencia_usuario_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, \WE\ReportBundle\Entity\Usuario $usuario) {
        $userManager = $this->get('fos_user.user_manager');
        $deleteForm = $this->createDeleteForm($usuario);
        $editForm = $this->createForm('WE\ReportBundle\Form\UsuarioAgenciaType', $usuario);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $usuario->setAgencia($this->getUser()->getAgencia());
            $userManager->updateUser($usuario);
            return $this->redirectToRoute('agencia_usuario_edit', array('id' => $usuario->getId()));
        }

        return $this->render('ReportBundle:Agencia:usuario/edit.html.twig', array(
                    'usuario' => $usuario,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a proyecto entity.
     *
     * @Route("/{id}", name="agencia_usuario_delete")
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

        return $this->redirectToRoute('agencia_usuario_index');
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
                        ->setAction($this->generateUrl('agencia_usuario_delete', array('id' => $usuario->getId())))
                        ->setMethod('DELETE')
                        ->getForm()
        ;
    }

}
