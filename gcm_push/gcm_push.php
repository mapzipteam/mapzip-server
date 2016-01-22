<?php 
include("../mapzip-gcm-define.php");
include("gcm-pusher-module.php");

$devices = array();
array_push($devices, 'faCSYKs_G6k:APA91bFQcEdwLWfjPZoCbhspR9QGGudF9fJE-QIWFxEgB9SX63-Bu2fHYgE4ktvlBgX0c3YntZ1Bvl6pGcuNPWldHI1-Khm0L1U59-1N2EVmHXA4wEfTF6JESRHM5BgB8hWxlqT5C4yR');
//array_push($devices, 'faCSYKs_G6k:APA91bFQcEdwLWfjPZoCbhspR9QGGudF9fJE-QIWFxEgB9SX63-Bu2fHYgE4ktvlBgX0c3YntZ1Bvl6pGcuNPWldHI1-Khm0L1U59-1N2EVmHXA4wEfTF6JESRHM5BgB8hWxlqT5C4yR');
array_push($devices, 'cSqdhZwPYbY:APA91bEccuEWLSeEzt23VX8GHcfg8YHfxDUybHhA-g82UnN0gBr-oMLZs9q-CLUSjYvFJx1MCvSVsFbasX5z4Zl5LYv2sXo1NsLRyA-1dRgrIqY5LNPJ4kKLWj7M2BG_ThTRBg0hgNim');


$title = "Mapzip";
$message = "안녕하세요, Mapzip 개발팀 입니다";

$response="";

//Array를 MULTICAST_COUNT값으로 나누자.
$divide_array = array_chunk($devices, MULTICAST_MAX);
 
for($j=0; $j < count($divide_array); $j++) {
 
    $gcm = new GCMPushMessage(API_SERVER_KEY);
    $gcm->setDevices($divide_array[$j]);
    $response = $gcm->send($title, $message, $link);
}

print_r($response);
?>