<?php 
include("../mapzip-gcm-define.php");
include("gcm-pusher-module.php");
include("../mapzip-mysql-define.php");
include("../fmysql.php");


if(!$conn = connect_mysqli(MYSQL_IP,MAIN_DB,DB_PASSWORD,USE_DB)){
	//echo "connnection error!\n";
	$to_client['state']=DB_CONNECTION_ERROR;
}
else{
//echo "connect success!\n";
}

$devices = array();

$sql = "SELECT * FROM ".GCM_TABLE;
if(!$result = mysqli_query($conn,$sql)){
	echo("GCM TABLE select fail...<br>");
}else{
	while($row = mysqli_fetch_assoc($result)){
		array_push($devices, $row['gcm_key']);
	}
	$title = $_POST['gcm_title'];
	$message = $_POST['gcm_message'];
	$extra = array();
	$extra['customkey1'] = "custom_key1";
	$extra['customkey2'] = "custom_key2";

	$response="";

	//Array를 MULTICAST_COUNT값으로 나누자.
	$divide_array = array_chunk($devices, MULTICAST_MAX);
 
	for($j=0; $j < count($divide_array); $j++) {
 
    	$gcm = new GCMPushMessage(API_SERVER_KEY);
    	$gcm->setDevices($divide_array[$j]);
    	$response = $gcm->send($title, $message, $extra);
	}	

	//$response = var_export($response);
}


?>