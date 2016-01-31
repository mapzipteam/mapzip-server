<?php
include("../fmysql.php");
include("../mapzip-state-define.php");
include("../mapzip-mysql-define.php");

$value = json_decode(file_get_contents('php://input'), true);

if(!$conn = connect_mysqli(MYSQL_IP,MAIN_DB,DB_PASSWORD,USE_DB)){
	//echo "connnection error!\n";
	$to_client['state']=DB_CONNECTION_ERROR;
}
else{
//echo "connect success!\n";
}

$sql = "UPDATE ".REVIEW_TABLE." SET map_id = {$value['map_id']}, review_emotion = {$value['review_emotion']}, review_text = '{$value['review_text']}', image_num = {$value['image_num']} WHERE user_id = '{$value['user_id']}' and pid = {$value['store_id']}";

if(!mysqli_query($conn,$sql)){
		// insert fail
	$to_client['state'] = CLIENT_REVIEW_DATA_UPDATE_FAIL;
}else{

	$to_client['state'] = CLIENT_REVIEW_DATA_UPDATE_SUCCESS;
}
echo json_encode($to_client);

?>