<?php

 $headers = array(
        'Content-Type:application/json'
            
    );
 $arr = array();
 $arr['from_name'] = "껄껄";
 $arr['to_id'] = 'aaammm';
 
$ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://ljs93kr.cafe24.com/mapzip/gcm_push/gcm_push_each.php');
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