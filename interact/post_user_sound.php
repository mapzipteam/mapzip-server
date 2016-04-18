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
		// db connection success
		$sql = "INSERT INTO ".USER_SOUND_TABLE." values(null, '{$value['user_id']}', '{$value['user_name']}', '{$value['category']}', '{$value['contents']}', 0, now());";

		if(!mysqli_query($conn,$sql)){
	// insert fail
			$to_client->setFields('state', USER_SOUND_INSERT_FAIL);
			$to_client->setDebugs('insert user sound table fail', $sql);
		}else{
			$to_client->setFields('state', USER_SOUND_INSERT_SUCCESS);
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


	$sql = "INSERT INTO ".USER_SOUND_TABLE." values(null, '{$value['userid']}', '{$value['username']}', '{$value['category']}', '{$value['contents']}', 0, now());";

	if(!mysqli_query($conn,$sql)){
	// insert fail
		$to_client['state'] = USER_SOUND_INSERT_FAIL;
	}else{
		$to_client['state'] = USER_SOUND_INSERT_SUCCESS;
	}

	echo json_encode($to_client);

}



?>