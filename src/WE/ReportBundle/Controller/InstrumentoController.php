<?php

namespace WE\ReportBundle\Controller;

use WE\ReportBundle\Entity\Instrumento;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Instrumento controller.
 *
 * @Route("instrumento")
 */
class InstrumentoController extends Controller
{
    /**
     * Lists all instrumento entities.
     *
     * @Route("/", name="instrumento_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $instrumentos = $em->getRepository('ReportBundle:Instrumento')->findAll();

        return $this->render('instrumento/index.html.twig', array(
            'instrumentos' => $instrumentos,
        ));
    }

    /**
     * Creates a new instrumento entity.
     *
     * @Route("/new", name="instrumento_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $instrumento = new Instrumento();
        $form = $this->createForm('WE\ReportBundle\Form\InstrumentoType', $instrumento);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($instrumento);
            $em->flush();

            return $this->redirectToRoute('instrumento_show', array('id' => $instrumento->getId()));
        }

        return $this->render('instrumento/new.html.twig', array(
            'instrumento' => $instrumento,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a instrumento entity.
     *
     * @Route("/{id}", name="instrumento_show")
     * @Method("GET")
     */
    public function showAction(Instrumento $instrumento)
    {
        $deleteForm = $this->createDeleteForm($instrumento);

        return $this->render('instrumento/show.html.twig', array(
            'instrumento' => $instrumento,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing instrumento entity.
     *
     * @Route("/{id}/edit", name="instrumento_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Instrumento $instrumento)
    {
        $deleteForm = $this->createDeleteForm($instrumento);
        $editForm = $this->createForm('WE\ReportBundle\Form\InstrumentoType', $instrumento);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('instrumento_edit', array('id' => $instrumento->getId()));
        }

        return $this->render('instrumento/edit.html.twig', array(
            'instrumento' => $instrumento,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a instrumento entity.
     *
     * @Route("/{id}", name="instrumento_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Instrumento $instrumento)
    {
        $form = $this->createDeleteForm($instrumento);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($instrumento);
            $em->flush();
        }

        return $this->redirectToRoute('instrumento_index');
    }

    /**
     * Creates a form to delete a instrumento entity.
     *
     * @param Instrumento $instrumento The instrumento entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Instrumento $instrumento)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('instrumento_delete', array('id' => $instrumento->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
