<?php

namespace WE\ReportBundle\Controller\Agencia;

use Symfony\Component\HttpFoundation\Request;
use WE\ReportBundle\Controller\MainController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/agencia/comentario")
 */
class ComentarioController extends MainController {

    public function notificationAction(Request $request) {
        return $this->render(
            'ReportBundle:Agencia:comentario/_list.html.twig', array('entity' => $this->getComentario())
        );
    }

    /**
     *
     * @Route("/get/comment", name="agencia_get_comentario")
     * @Method("GET")
     */
    public function commentAction() { 
        // return new \Symfony\Component\HttpFoundation\JsonResponse(array('id' => $this->getComentario()->getId(),'mensaje' => $this->getComentario()->getComentario()));

            return $this->render(
            'ReportBundle:Agencia:comentario/_list.html.twig', array('entity' => $this->getComentario())
            );

    }

    protected function getComentario() {
        $em = $this->getDoctrine()->getManager();

        $activaciones = $em->getRepository('ReportBundle:Activacion')->findByBrand(null, null, $this->getUser());
        $entities = $em->getRepository('ReportBundle:ActivacionComentario')->findByComentariosAgencia($activaciones);

        return array_pop($entities);
    }

    /**
     *
     * @Route("/rate/{id}/{rate}", name="agencia_comentario_rate")
     * @Method("GET")
     */
    public function indexAction($id, $rate) {
        $em = $this->getDoctrine()->getManager();
        $data = array('status' => 'error');
        $comentario = $em->getRepository('ReportBundle:ActivacionComentario')->find($id);
        $calification = array(1 => 'Malo', 2 => 'Regular', 3 => 'Bueno');

        if ($calification[$rate]) {
            $data = array('status' => 'ok', 'rate' => $calification[$rate]);

            $comentario->setRating($calification[$rate]);
            $em->persist($comentario);
            $em->flush();
        }

        return new \Symfony\Component\HttpFoundation\JsonResponse($data);
    }

}
