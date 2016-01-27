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

?>