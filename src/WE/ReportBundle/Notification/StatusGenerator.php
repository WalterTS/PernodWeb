<?php

namespace WE\ReportBundle\Notification;

use WE\ReportBundle\Entity\Activacion;
use WE\ReportBundle\Entity\Proyecto;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of StatusGenerator
 *
 * @author dioner911
 */
class StatusGenerator extends Controller {

    private $router;
    private $em;
    private $mailer;

    const KII_APP_ID = "ugd33v4u2oye";
    const KII_APP_KEY = "1cf33110046c402cb57436bfc2726168";
    const KII_CLIENT_ID = "f63c37899cf119c293672d2e1819d7d1";
    const KII_CLIENT_SECRET = "e5fb6cbcde388d1791be28ac4aba555d736ecc4db3ac96e980b01addef98d4e8";

    public function __construct(UrlGeneratorInterface $router, \Swift_Mailer $mailer, EntityManagerInterface $em) {
        $this->router = $router;
        $this->mailer = $mailer;
        $this->em = $em;
    }

    public function setProyecto(Proyecto $proyecto) {
        //BUSCO KAMS EN BASE A LA REGIÓN
        $kams = $this->findByRole('ROLE_USER_KAM', $proyecto->getRegiones());
        $ejecutivos = $this->findByRole('ROLE_USER_EJECUTIVO', $proyecto->getCdcs());

        $text = "Se creo un proyecto en tu región";
        $path = $this->router->generate('activacion_new');
        $this->createNotification($kams, $text, $text, null, $path);

        $text = "Se creo un proyecto para tus CDCS";
        $this->createNotification($ejecutivos, $text, $text, null, $path);

        $text = "Se asigno un proyecto";

        $description = "Marca: " . $proyecto->getMarca()->getNombre() . " \n"
                . "Plazas: " . $proyecto->getPlazasString() . " \n"
                . "Total de activaciones: " . $proyecto->getTotalActivaciones() . " \n"
                . "Objetivo de ventas por activacion: " . $proyecto->getKpiTotal();

        $this->createNotification($this->findByRole('ROLE_USER_CUENTA', $proyecto->getAgencia()->getUsuarios()), $text, $description);
    }

    protected function findByRole($role, $data) {
        return $this->em->getRepository('ReportBundle:Usuario')->findByRole($role, $data);
    }

    protected function getIds($data) {
        $serializer = array();
        foreach ($data as $entry) {
            $serializer[] = $entry->getId();
        }

        return $serializer;
    }

    public function setStatus(Activacion $activacion) {

        // ESTO SE EJECUTA CUANDO CAMBIO YA EL STATUS DE DA LA ACTIVACIÓN

        switch ($activacion->getStatus()->getId()) {
            case 1:
                //DEBO DE NOTIFICAR AL USUARIO GERENTE DE MARCA
                $text = "Tienes una activación por aprobar: " . $activacion->getCdc()->getNombre() . ' @ ' . $activacion->getCdc()->getPlaza()->getAbreviacion();
                /// CREAR PATH DE APROBACIÖN DE ACTIVACION 
                $path = $this->router->generate('activacion_show', array('id' => $activacion->getId()));
                $this->createNotification($this->findUsers('Gerente de marca', $activacion), $text, $text, $activacion, $path);
                break;
            case 2:
                // DEBO DE NOTIFICAR AL USUARIO EJECUTIVO DE CUENTA
                $text = "Se aprobó una activación en: " . $activacion->getCdc()->getNombre() . ' @ ' . $activacion->getCdc()->getPlaza()->getAbreviacion() . '  Asigna este proyecto a un productor.';
                $path = $this->router->generate('activacion_show', array('id' => $activacion->getId()));
                $this->createNotification($this->findUsers('Ejecutivo de cuenta', $activacion), $text, $text, $activacion, $path);

                $text_kam = "Se aprobó una activación en: " . $activacion->getCdc()->getNombre() . ' @ ' . $activacion->getCdc()->getPlaza()->getAbreviacion();
                $this->createNotification($this->findUsers('KAM', $activacion), $text_kam, $text_kam, $activacion, $path);

                break;
            case 3:
                // DEBO DE NOTIFICAR AL USUARIO KAM
                $text = "Se rechazo una activación en: " . $activacion->getCdc()->getNombre() . ' @ ' . $activacion->getCdc()->getPlaza()->getAbreviacion();
                $path = $this->router->generate('activacion_show', array('id' => $activacion->getId()));

                $this->createNotification($this->findUsers('KAM', $activacion), $text, $text, $activacion, $path);
                break;
            case 4:
                // DEBO DE NOTIFICAR AL USUARIO EJECUTIVO DE CUENTA
                $text = "Confirma la información del reporte: " . $activacion->getCdc()->getNombre() . ' @ ' . $activacion->getCdc()->getPlaza()->getAbreviacion();
                $path = $this->router->generate('activacion_show', array('id' => $activacion->getId()));
                $this->createNotification($this->findUsers('Productor', $activacion), $text, $text, $activacion, $path);
                break;
            case 5:
                // DEBO DE NOTIFICAR AL USUARIO GERENTE DE MARCA
                $text = "Revisa el reporte online de la activación: " . $activacion->getCdc()->getNombre() . ' @ ' . $activacion->getCdc()->getPlaza()->getAbreviacion();
                $path = $this->router->generate('activacion_show', array('id' => $activacion->getId()));
                $this->createNotification($this->findUsers('Gerente de marca', $activacion), $text, $text, $activacion, $path);
                break;
            case 6:
                $text = "Verifica tu checkin en: " . $activacion->getCdc()->getNombre() . ' @ ' . $activacion->getCdc()->getPlaza()->getAbreviacion();
                $path = $this->router->generate('capture_app', array('app_id' => $activacion->getId()));
                $this->createNotification($this->findUsers('Supervisor', $activacion), $text, $text, $activacion, $path);
                break;
            case 7:
                $text = "Realizar reporte de la activación en: " . $activacion->getCdc()->getNombre() . ' @ ' . $activacion->getCdc()->getPlaza()->getAbreviacion();
                $path = $this->router->generate('capture_app', array('app_id' => $activacion->getId()));
                $this->createNotification($this->findUsers('Supervisor', $activacion), $text, $text, $activacion, $path);

                $text_gerente = "Inicio la activación de: " . $activacion->getCdc()->getNombre() . ' @ ' . $activacion->getCdc()->getPlaza()->getAbreviacion();
                $this->createNotification($this->findUsers('Gerente de marca', $activacion), $text_gerente, $text_gerente, $activacion, $path);
                break;
            case 8:
                // DEBO DE NOTIFICAR AL USUARIO GERENTE DE MARCA
                $text = "Revisa el reporte online de la activación: " . $activacion->getCdc()->getNombre() . ' @ ' . $activacion->getCdc()->getPlaza()->getAbreviacion();
                $path = $this->router->generate('activacion_show', array('id' => $activacion->getId()));
                $this->createNotification($this->findUsers('Gerente de marca', $activacion), $text, $text, $activacion, $path);
                break;
            case 9:
                // DEBO DE NOTIFICAR AL USUARIO PRODUCTOR
                $text = "Inicio la activación en: " . $activacion->getCdc()->getNombre() . ' @ ' . $activacion->getCdc()->getPlaza()->getAbreviacion();
                $path = $this->router->generate('activacion_show', array('id' => $activacion->getId()));
                $this->createNotification($this->findUsers('Productor', $activacion), $text, $text, $activacion, $path);
                break;
            case 10:
                // DEBO DE NOTIFICAR AL USUARIO EJECUTIVO DE CUENTA
                $text = "Confirma la información del reporte: " . $activacion->getCdc()->getNombre() . ' @ ' . $activacion->getCdc()->getPlaza()->getAbreviacion();
                $path = $this->router->generate('activacion_show', array('id' => $activacion->getId()));
                $this->createNotification($this->findUsers('Ejecutivo de cuenta', $activacion), $text, $text, $activacion, $path);
                break;

            case 12:
                // DEBO DE NOTIFICAR AL USUARIO EJECUTIVO DE CUENTA
                $text = "Inicio la activación en: " . $activacion->getCdc()->getNombre() . ' @ ' . $activacion->getCdc()->getPlaza()->getAbreviacion();
                $path = $this->router->generate('activacion_show', array('id' => $activacion->getId()));
                $this->createNotification($this->findUsers('Ejecutivo de cuenta', $activacion), $text, $text, $activacion, $path);
                break;
            case 13:
                // DEBO DE NOTIFICAR AL USUARIO EJECUTIVO DE CUENTA
                $text = "El reporte de " . $activacion->getCdc()->getNombre() . ' @ ' . $activacion->getCdc()->getPlaza()->getAbreviacion() . ' necesita ser revisado.';
                $path = $this->router->generate('capture_app', array('app_id' => $activacion->getId()));
                $this->createNotification($this->findUsers('Supervisor', $activacion), $text, $text, $activacion, $path);
                break;
            case 14:
                // DEBO DE NOTIFICAR AL USUARIO EJECUTIVO DE CUENTA
                $text = "El reporte de " . $activacion->getCdc()->getNombre() . ' @ ' . $activacion->getCdc()->getPlaza()->getAbreviacion() . ' necesita ser revisado.';
                $path = $this->router->generate('capture_app', array('app_id' => $activacion->getId()));
                $this->createNotification($this->findUsers('Productor', $activacion), $text, $text, $activacion, $path);
                break;
        }
    }

    public function setProducers($activacion) {
        $text = "Tienes un nuevo proyecto el: " . $activacion->getFecha()->format('d-M-y') . " en " . $activacion->getCdc()->getNombre() . ' @ ' . $activacion->getCdc()->getPlaza()->getAbreviacion() . ' Asigna al supervisor encargado.';
        $path = $this->router->generate('activacion_show', array('id' => $activacion->getId()));
        $this->createNotification($this->findUsers('Productor', $activacion), $text, $text, $activacion, $path);
    }

    public function setSupervisors($activacion) {
        $text = "Tienes un nuevo proyecto para el:  " . $activacion->getFecha()->format('d-M-y') . " en " . $activacion->getCdc()->getNombre() . ' @ ' . $activacion->getCdc()->getPlaza()->getAbreviacion();
        $path = $this->router->generate('capture_app', array('app_id' => $activacion->getId()));
        $this->createNotification($this->findUsers('Supervisor', $activacion), $text, $text, $activacion, $path);
    }

    protected function findUsers($tipo, $activacion) {
        return $activacion->getUsuarios()->filter(function($usuario) use ($tipo) {
                    return $usuario->getTipo() === $tipo;
                });
    }

    protected function createNotification($users, $title, $text, $activacion = null, $path = null) {
        if ($users) {
            foreach ($users as $user) {
                $u = $user instanceof \WE\ReportBundle\Entity\Usuario ? $user : $user->getUsuario();
                $notification = new \WE\ReportBundle\Entity\Notificacion();
                $notification->setTitulo($title);
                $notification->setContenido($text);
                $notification->setPath($path);
                $notification->setUserTo($u);
                $notification->setStatus(false);
                $notification->setFecha(new \DateTime('now'));
                $this->em->persist($notification);
                $this->notificateAction($title, $text, $u->getId(), $path);
            }
        }

        $this->em->flush();
    }



    //#################################
    //#################################
    //########## KII METHODS ##########
    //#################################
    //#################################


    public function notificateAction($title, $mensaje, $id, $path) {
        $device = "user_".$id;
        // $mensaje = "¡EL MEJOR PRECIO EN AMIGO KIT! ZTE BLADE A6 MAX POR SOLO $2,599";
        
        return StatusGenerator::createIosNotification($this->fetchUserId($device), $title, $mensaje, true, $path);
    }

    protected function fetchUserId($codigo) {
        $url = "https://api.kii.com/api/oauth2/token";


        $arrayToPost = array(
            'client_id' => StatusGenerator::KII_CLIENT_ID,
            'client_secret' => StatusGenerator::KII_CLIENT_SECRET,
        );


        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($arrayToPost));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Kii-AppID: " . StatusGenerator::KII_APP_ID, "X-Kii-AppKey: " . StatusGenerator::KII_APP_KEY, "Content-Type: application/json"));

        $result = json_decode(curl_exec($ch));

        $access_token = $result->access_token;


        $url_push = "https://api.kii.com/api/apps/" . StatusGenerator::KII_APP_ID . "/users/LOGIN_NAME:" . $codigo;
        $headers = array("Authorization: Bearer " . $access_token);


        $ch2 = curl_init();

        curl_setopt($ch2, CURLOPT_URL, $url_push);
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch2, CURLOPT_USERAGENT, 'Codular Sample cURL Reques');
        curl_setopt($ch2, CURLOPT_HTTPHEADER, $headers);
        $result = json_decode(curl_exec($ch2));

        return $result ? $result->userID : null;
    }

    /**
     * @Route("/notification/create/topic/{topic}", name="topic_create")
     */
    public function topicAction(Request $request, $topic) {
        $url = "https://api.kii.com/api/oauth2/token";


        $arrayToPost = array(
            'client_id' => StatusGenerator::KII_CLIENT_ID,
            'client_secret' => StatusGenerator::KII_CLIENT_SECRET,
        );

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($arrayToPost));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Kii-AppID: " . StatusGenerator::KII_APP_ID, "X-Kii-AppKey: " . StatusGenerator::KII_APP_KEY, "Content-Type: application/json"));

        $result = json_decode(curl_exec($ch));

        $access_token = $result->access_token;

        $url_push = "https://api.kii.com/api/apps/" . StatusGenerator::KII_APP_ID . "/topics/" . $topic;
        $headers = array("Authorization: Bearer " . $access_token);

        curl_setopt($ch, CURLOPT_URL, $url_push);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_PUT, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = json_decode(curl_exec($ch));

        print_r($result);

        exit;
    }

    public static function createIosNotification($topic, $title = null, $sms = null, $user = false, $path) {

        $url = "https://api.kii.com/api/oauth2/token";


        $arrayToPost = array(
            'client_id' => StatusGenerator::KII_CLIENT_ID,
            'client_secret' => StatusGenerator::KII_CLIENT_SECRET,
        );


        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($arrayToPost));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Kii-AppID: " . StatusGenerator::KII_APP_ID, "X-Kii-AppKey: " . StatusGenerator::KII_APP_KEY, "Content-Type: application/json"));

        $result = json_decode(curl_exec($ch));

        $access_token = $result->access_token;

        $sms = $sms ? $sms : "Actualice title y sms";
        $title = $title ? $title : "Main Thread";

        $message = array('data' => array(
            "MsgBody" => $sms,
            "titleAndroid" => $title,
            "path" => $path,
                "Priority" => 1,
                "Urgent" => false),
            "sendToDevelopment" => false,
            "sendToProduction" => true,
            "gcm" =>
            array("enabled" => true)
            ,
            "apns" => array(
                "enabled" => true,
                "sound" => "alert",
                "badge" => 0,
                "alert" => array('body' => $sms, 'title' => $title)
            )
        );


        //SE DEBERA DE LOOPEAR
        if (!$user) {
            $url_push = "https://api.kii.com/api/apps/" . StatusGenerator::KII_APP_ID . "/topics/" . $topic . "/push/messages";
        } else {
            $url_push = "https://api.kii.com/api/apps/" . StatusGenerator::KII_APP_ID . "/users/" . $topic . "/push/messages";
        }

        $headers = array("Authorization: Bearer " . $access_token,
            "X-Kii-AppID: " . StatusGenerator::KII_APP_ID,
            "X-Kii-AppKey: " . StatusGenerator::KII_APP_KEY,
            "Content-Type: application/vnd.kii.SendPushMessageRequest+json",
            "Accept: application/vnd.kii.SendPushMessageResponse+json");
        curl_setopt($ch, CURLOPT_URL, $url_push);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($message));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = json_decode(curl_exec($ch));

        return new \Symfony\Component\HttpFoundation\JsonResponse($result);
    }


}
