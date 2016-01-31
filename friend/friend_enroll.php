<?php

include("../fmysql.php");
include("../mapzip-mysql-define.php");
include("../mapzip-state-define.php");
include("../mapzip-gcm-define.php");

$value = json_decode(file_get_contents('php://input'), true);

if(!$conn = connect_mysqli(MYSQL_IP,MAIN_DB,DB_PASSWORD,USE_DB)){
	//echo "connnection error!\n";
	$to_client['state']=DB_CONNECTION_ERROR;
}
else{
//echo "connect success!\n";
}

//$sql = "INSERT INTO ".CLIENT_TABLE.$value['user_id']." ( type, friend_id, created) VALUES (".CLIENT_TYPE_FRIEND.",'{$value['friend_id']}',now())";
$sql = "INSERT INTO ".FRIEND_TABLE." (from_id, to_id) VALUES ('{$value['user_id']}', '{$value['friend_id']}');";
if(!$result = mysqli_query($conn,$sql)){
	//echo "query fail...\n";
	$to_client['state_log']=$sql;
}
else{
	$to_client['state']="insert success";
	
		$headers = array(
        'Content-Type:application/json'
            
    	);
		$arr = array();
		$arr['from_name'] = $value['user_name'];
		$arr['to_id'] = $value['friend_id'];
		$arr['gcm_type'] = WHEN_ADD_FRIEND;

		$ch = curl_init();
    	curl_setopt($ch, CURLOPT_URL, 'http://ljs93kr.cafe24.com/mapzip/gcm_push/gcm_push_each.php');
    	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    	curl_setopt($ch, CURLOPT_POST, true);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    	curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($arr));
    	$response = curl_exec($ch);
    	curl_close($ch);

	
	
}

echo json_encode($to_client);



?>