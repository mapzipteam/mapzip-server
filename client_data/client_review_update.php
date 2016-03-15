<?php
include("../fmysql.php");
include("../mapzip-state-define.php");
include("../mapzip-mysql-define.php");

$value = json_decode(file_get_contents('php://input'), true);

if(($value['build_version'] >= BUILD_VERSION_GARNET) && ($value['build_version'] < BUILD_VERSION_GARNET_END)){
	$to_client = new MapzipResponse($value['debug_mode'], $value['build_version']);
	
	if(!$conn = connect_mysqli(MYSQL_IP,MAIN_DB,DB_PASSWORD,USE_DB)){
	//echo "connnection error!\n";
		$to_client->setFields("state", DB_CONNECTION_ERROR);
		$to_client->setDebugs("DB connection error", DB_CONNECTION_ERROR);
	}
	else{
//echo "connect success!\n";
	}

	$sql = "UPDATE ".REVIEW_TABLE." SET map_id = {$value['map_id']}, review_emotion = {$value['review_emotion']}, positive_text = '{$value['positive_text']}', negative_text = '{$value['negative_text']}', review_text = '{$value['review_text']}', image_num = {$value['image_num']}, modified = now() WHERE user_id = '{$value['user_id']}' and pid = {$value['store_id']}";

	if(!mysqli_query($conn,$sql)){
		// insert fail
		$to_client->setFields('state', CLIENT_REVIEW_DATA_UPDATE_FAIL);
		$to_client->setDebugs('update review table error', $sql);
	}else{
		$to_client->setFields('state', CLIENT_REVIEW_DATA_UPDATE_SUCCESS);

		$sql = "UPDATE ".CLIENT_TABLE." SET created = now() WHERE user_id = '{$value['user_id']}' and map_id = {$value['map_id']}";
		if(!mysqli_query($conn, $sql)){
			$to_client->setDebugs('update client table create error', $sql);
		}
	}
	echo json_encode($to_client->build());
}else{
	if(!$conn = connect_mysqli(MYSQL_IP,MAIN_DB,DB_PASSWORD,USE_DB)){
	//echo "connnection error!\n";
		$to_client['state']=DB_CONNECTION_ERROR;
	}
	else{
//echo "connect success!\n";
	}

	$sql = "UPDATE ".REVIEW_TABLE." SET map_id = {$value['map_id']}, review_emotion = {$value['review_emotion']}, positive_text = '{$value['positive_text']}', negative_text = '{$value['negative_text']}', review_text = '{$value['review_text']}', image_num = {$value['image_num']}, modified = now() WHERE user_id = '{$value['user_id']}' and pid = {$value['store_id']}";

	if(!mysqli_query($conn,$sql)){
		// insert fail
		$to_client['state'] = CLIENT_REVIEW_DATA_UPDATE_FAIL;
	}else{
		$to_client['state'] = CLIENT_REVIEW_DATA_UPDATE_SUCCESS;

		$sql = "UPDATE ".CLIENT_TABLE." SET created = now() WHERE user_id = '{$value['user_id']}' and map_id = {$value['map_id']}";
		mysqli_query($conn, $sql);
	}
	echo json_encode($to_client);
}



?>