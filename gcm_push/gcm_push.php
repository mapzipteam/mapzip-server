<?php 
include("../mapzip-gcm-define.php");
include("gcm-pusher-module.php");

$devices = array();
array_push($devices, 'faCSYKs_G6k:APA91bFQcEdwLWfjPZoCbhspR9QGGudF9fJE-QIWFxEgB9SX63-Bu2fHYgE4ktvlBgX0c3YntZ1Bvl6pGcuNPWldHI1-Khm0L1U59-1N2EVmHXA4wEfTF6JESRHM5BgB8hWxlqT5C4yR');

$title = "Mapzip";
$message = "안녕하세요, Mapzip 개발팀 입니다";

$gcm = new GCMPushMessage(API_SERVER_KEY);
$gcm->setDevices($devices);
$response = $gcm->send($title, $message, $link);

?>