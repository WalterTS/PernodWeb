<?php

namespace WE\ReportBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use WE\ReportBundle\Entity\ActivacionComentario;
use WE\ReportBundle\Entity\Activacion;
use Symfony\Component\HttpFoundation\Request;
use WE\ReportBundle\Entity\Notificacion;

/**
 * @Route("/inbox")
 */
class NotificationController extends Controller {

    /**
     * @Route("/redirect/{id}", name="inbox_redirect")
     */
    public function redirectAction(Notificacion $message) {
        $em = $this->getDoctrine()->getManager();

        $message->setStatus(true);
        $em->persist($message);
        $em->flush();

        $route = $message->getPath() ? $message->getPath() : $this->generateUrl('inbox');

        return $this->redirect($route);
    }

    /**
     * @Route("/supervisor/post", name="inbox_supervisor_post")
     */
    public function addCommentSupervisorAction(Request $request) {
        $comentario = new ActivacionComentario();

        $form = $this->createForm('WE\ReportBundle\Form\ActivacionComentarioSupervisorType', $comentario);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $comentario->setFecha(new \DateTime());
            $em->persist($comentario);
            $em->flush();

            $request->getSession()
                    ->getFlashBag()
                    ->add('success', $comentario->getTipo(). ' publicad@ corectamente.')
            ;
        }

        return $this->redirectToRoute('capture_app', array('app_id' => $comentario->getActivacion()->getId()));
    }

    /**
     * @Route("/post", name="inbox_post")
     */
    public function addCommentAction(Request $request) {
        $comentario = new ActivacionComentario();

        $form = $this->createForm('WE\ReportBundle\Form\ActivacionComentarioType', $comentario);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $comentario->setFecha(new \DateTime());
            $em->persist($comentario);
            $em->flush();
        }


        return $this->redirectToRoute('activacion_show', array('id' => $comentario->getActivacion()->getId()));
    }

    /**
     * @Route("/{message_id}", name="inbox",defaults={"message_id"=0})
     */
    public function inboxAction(Request $request, $message_id) {
        $usuario = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        $notifications = $usuario->getNotificacionesRecibidas();

        if (!$message_id) {
            $notification = $notifications->last();
        } else {
            $notification = $em->getRepository('ReportBundle:Notificacion')->find($message_id);
        }
        if ($notification) {
            $notification->setStatus(true);
            $em->persist($notification);
        }
        $em->flush();
        if ($request->isXmlHttpRequest()) {

            return $this->render('ReportBundle:sections:_inbox_content.html.twig', array('entities' => $notifications, 'notification' => $notification));
        }

        return $this->render('ReportBundle:notification:inbox.html.twig', array('entities' => $notifications, 'notification' => $notification));
    }

    public function unreadNotificationsAction($position = "") {
        $usuario = $this->getUser();
        return $this->render('ReportBundle:notification:_list.html.twig', array('entities' => $usuario->getUnreadNotifications(), 'position' => $position));
    }

    public function commentsAction(Activacion $activacion) {
        $comentario = new ActivacionComentario();
        $comentario->setUserFrom($this->getUser());
        $comentario->setActivacion($activacion);

        $form = $this->createForm('WE\ReportBundle\Form\ActivacionComentarioType', $comentario);

        return $this->render('ReportBundle:activaciones:_comments.html.twig', array('form' => $form->createView(), 'activacion' => $activacion));
    }

}
