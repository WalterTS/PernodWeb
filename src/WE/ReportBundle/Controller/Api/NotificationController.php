<?php

namespace WE\ReportBundle\Controller\Api;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/notification")
 */
class NotificationController extends Controller {

    /**
     * @Route("/send", name="notification_send")
     */
    public function notificateAction(Request $request) {
        $topic = "MainTopic";
        return $this->createIosNotification($topic, $request->get('title'), $request->get('content'));
    }

    protected function fetchUserId($codigo) {
        $url = "https://api.kii.com/api/oauth2/token";


        $arrayToPost = array(
            'client_id' => $this->container->getParameter('kii_client_id'),
            'client_secret' => $this->container->getParameter('kii_client_secret'),
        );


        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($arrayToPost));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Kii-AppID: " . $this->container->getParameter('kii_app_id'), "X-Kii-AppKey: " . $this->container->getParameter('kii_app_key'), "Content-Type: application/json"));

        $result = json_decode(curl_exec($ch));

        $access_token = $result->access_token;


        $url_push = "https://api.kii.com/api/apps/" . $this->container->getParameter('kii_app_id') . "/users/LOGIN_NAME:" . $codigo;
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
     * @Route("/create/topic/{topic}", name="topic_create")
     */
    public function topicAction(Request $request, $topic) {
        $url = "https://api.kii.com/api/oauth2/token";


        $arrayToPost = array(
            'client_id' => $this->container->getParameter('kii_client_id'),
            'client_secret' => $this->container->getParameter('kii_client_secret'),
        );


        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($arrayToPost));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Kii-AppID: " . $this->container->getParameter('kii_app_id'), "X-Kii-AppKey: " . $this->container->getParameter('kii_app_key'), "Content-Type: application/json"));

        $result = json_decode(curl_exec($ch));

        $access_token = $result->access_token;

        $url_push = "https://api.kii.com/api/apps/" . $this->container->getParameter('kii_app_id') . "/topics/" . $topic;
        $headers = array("Authorization: Bearer " . $access_token);

        curl_setopt($ch, CURLOPT_URL, $url_push);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_PUT, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = json_decode(curl_exec($ch));

        print_r($result);

        exit;
    }

    public function createIosNotification($topic, $title = null, $sms = null, $user = false) {

        $url = "https://api.kii.com/api/oauth2/token";


        $arrayToPost = array(
            'client_id' => $this->container->getParameter('kii_client_id'),
            'client_secret' => $this->container->getParameter('kii_client_secret'),
        );


        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($arrayToPost));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Kii-AppID: " . $this->container->getParameter('kii_app_id'), "X-Kii-AppKey: " . $this->container->getParameter('kii_app_key'), "Content-Type: application/json"));

        $result = json_decode(curl_exec($ch));

        $access_token = $result->access_token;

        $sms = $sms ? $sms : "Actualice title y sms";
        $title = $title ? $title : "Main Thread";

        $message = array('data' => array("MsgBody" => $sms,
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


        if (!$user) {
            $url_push = "https://api.kii.com/api/apps/" . $this->container->getParameter('kii_app_id') . "/topics/" . $topic . "/push/messages";
        } else {
            $url_push = "https://api.kii.com/api/apps/" . $this->container->getParameter('kii_app_id') . "/users/" . $topic . "/push/messages";
        }

        $headers = array("Authorization: Bearer " . $access_token,
            "X-Kii-AppID: " . $this->container->getParameter('kii_app_id'),
            "X-Kii-AppKey: " . $this->container->getParameter('kii_app_key'),
            "Content-Type: application/vnd.kii.SendPushMessageRequest+json",
            "Accept: application/vnd.kii.SendPushMessageResponse+json");
        curl_setopt($ch, CURLOPT_URL, $url_push);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($message));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = json_decode(curl_exec($ch));

        print_r($result);
        exit;
    }

}
