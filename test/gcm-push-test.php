<?php
 
    $regid = "faCSYKs_G6k:APA91bFQcEdwLWfjPZoCbhspR9QGGudF9fJE-QIWFxEgB9SX63-Bu2fHYgE4ktvlBgX0c3YntZ1Bvl6pGcuNPWldHI1-Khm0L1U59-1N2EVmHXA4wEfTF6JESRHM5BgB8hWxlqT5C4yR";
 
    // 헤더 부분
    $headers = array(
            'Content-Type:application/json',
            'Authorization:key=AIzaSyD9qJ9IMJVed2MctQz6SzlpSuTzTJo8k4Q'
            );
 
    // 푸시 내용, data 부분을 자유롭게 사용해 클라이언트에서 분기할 수 있음.
    $arr = array();
    $arr['data'] = array();
    $arr['data']['title'] = '푸시 테스트';
    $arr['data']['message'] = '푸시 내용 ABCD~';
    $arr['registration_ids'] = array();
    $arr['registration_ids'][0] = $regid;
 
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://android.googleapis.com/gcm/send');
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($arr));
    $response = curl_exec($ch);
    curl_close($ch);
 
    // 푸시 전송 결과 반환.
    $obj = json_decode($response);
 
    // 푸시 전송시 성공 수량 반환.
    $cnt = $obj->{"success"};
 
    echo $cnt;
?>