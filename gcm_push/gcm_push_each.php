<?php
include("../mapzip-gcm-define.php");
include("gcm-pusher-module.php");
include("../mapzip-mysql-define.php");
include("../fmysql.php");

// requried params : from_name , to_id


$value = json_decode(file_get_contents('php://input'), true);

if(!$conn = connect_mysqli(MYSQL_IP,MAIN_DB,DB_PASSWORD,USE_DB)){
	//echo "connnection error!\n";
	$to_client['state']=DB_CONNECTION_ERROR;
}
else{
//echo "connect success!\n";
}

$devices = array();
$title = GCM_TITLE;
if($value['gcm_type'] === WHEN_ADD_FRIEND){
	// 다른유저가 나를 맵갈피에 추가했을때 작동
	$message = "{$value['from_name']}".ADD_FRIEND_STRING;
	$extra = array();
	$extra['customkey1'] = "custom_key1";
	$extra['customkey2'] = "custom_key2";

	$sql = "SELECT * FROM ".GCM_TABLE." WHERE user_id = '{$value['to_id']}'";
	if(!$result = mysqli_query($conn,$sql)){
		echo("GCM TABLE select fail...<br>");
	}else{
		$row = mysqli_fetch_assoc($result);
		array_push($devices,$row['gcm_key']);

		$gcm = new GCMPushMessage(API_SERVER_KEY);
    	$gcm->setDevices($devices);
    	$response = $gcm->send($title, $message, $extra);

	}
}else if($value['gcm_type'] === WHEN_FRIEND_NEW_REVIEW){
	// 맵갈피에 추가한 유저의 새로운 리뷰가 등록되었을때, 작동
	$message = "{$value['from_name']}".FRIEND_NEW_REVIEW_STRING;
	$extra = array();
	$extra['customkey1'] = "custom_key1";
	$extra['customkey2'] = "custom_key2";

	$friend_table = FRIEND_TABLE;
	$gcm_table = GCM_TABLE;
	$target_id = $value['from_id'];
	

	$sql = "SELECT {$friend_table}.from_id, {$gcm_table}.gcm_key FROM {$friend_table} INNER JOIN {$gcm_table} ON {$friend_table}.to_id = '{$target_id}' and {$friend_table}.from_id = {$gcm_table}.user_id";
	if(!$result = mysqli_query($conn,$sql)){
		echo "CLIENT_TABLE select fail..";
	}else{
		while($row = mysqli_fetch_assoc($result)){
			array_push($devices,$row['gcm_key']);
		}
		
		//Array를 MULTICAST_COUNT값으로 나누자.
		$divide_array = array_chunk($devices, MULTICAST_MAX);
		for($j=0; $j < count($divide_array); $j++) {
 
    		$gcm = new GCMPushMessage(API_SERVER_KEY);
    		$gcm->setDevices($divide_array[$j]);
    		$response = $gcm->send($title, $message, $extra);
		}	
	}

}


?>