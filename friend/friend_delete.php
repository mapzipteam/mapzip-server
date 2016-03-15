<?php
include("../fmysql.php");
include("../mapzip-mysql-define.php");
include("../mapzip-state-define.php");
include("../user-leave-module.php");

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

	$user_leave = new MapzipUserLeave($conn);
	$user_leave->setTargetId($value['user_id']);
	
	if($user_leave->deleteFromFriendTable(FRIEND_TYPE_DELETE_ONLYTOID, $value['target_id'])){
		$to_client->setDebugs("deleteFromFriendTable","success");
		$to_client->setFields("state", FRIEND_ITEM_DELETE_SUCCESS);
		
	}else{
		$to_client->setDebugs("deleteFromFriendTable", "fail");
		$to_client->setFields("state", FRIEND_ITEM_DELETE_FAIL);
		
	}
	echo json_encode($to_client->build());
}else{
	// old client version code
	$to_client['state'] = NON_KNOWN_ERROR;

	if(!$conn = connect_mysqli(MYSQL_IP,MAIN_DB,DB_PASSWORD,USE_DB)){
    //echo "connnection error!\n";
		$to_client['state_log'] .= "connect mysql fail...\n";

	}
	else{
		$to_client['state_log'] .= "connect mysql success!\n";
	}

	$user_leave = new MapzipUserLeave($conn);
	$user_leave->setTargetId($value['user_id']);
	if($user_leave->deleteFromFriendTable(FRIEND_TYPE_DELETE_ONLYTOID,$value['target_id'])){
		$to_client['state_log'] .= "delete success";
	}else{
		$to_client['state_log'] .= "delete something fail";
	}
	echo json_encode($to_client);
}



?>