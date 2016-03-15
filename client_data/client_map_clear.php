<?php
include("../fmysql.php");
include("../mapzip-mysql-define.php");
include("../mapzip-state-define.php");
include("../user-leave-module.php");
include("client_rmdir.php");

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

// delete review image dir
	if($user_leave->deleteReviewImageDir(REVIEW_TYPE_DELETE_ONEMAP,$value['map_id'])){
		$to_client->setDebugs("deleteReviewImageDir", "success");
		
	}else{
		$to_client->setDebugs("deleteReviewImageDir", "fail");
		$to_client->setFields("state", LEAVE_ERROR_IGNORE);

	}

// delete review data 
	if($user_leave->deleteFromReviewTable(REVIEW_TYPE_DELETE_ONEMAP, $value['map_id'])){
		$to_client->setDebugs("deleteFromReviewTable", "success");
		$sql = "SELECT count(*) as new_total_review FROM ".REVIEW_TABLE." WHERE user_id = '{$value['user_id']}'";
		if(!$result = mysqli_query($conn,$sql)){
			$to_client->setDebugs("select count new_total_review review table error", $sql);
		}else{
			$row = mysqli_fetch_assoc($result);
			$sql = "UPDATE ".USER_TABLE." SET total_review = {$row['new_total_review']} WHERE userid = '{$value['user_id']}'";
			if(!mysqli_query($conn,$sql)){
				$to_client->setDebugs("update total_review user table error", $sql);
			}else{
				$to_client->setDebugs("select count new_total_review review table", "success");
				$to_client->setFields("state", CLIENT_MAP_ONE_CLEAR_SUCCESS);
				
			}
		}
	}else{
		$to_client->setFields("state", CLIENT_MAP_ONE_CLEAR_FAIL);
		$to_client->setDebugs("deleteFromReviewTable", "fail");
	}


	echo json_encode($to_client->build());
}else{
	// old client version
	if(!$conn = connect_mysqli(MYSQL_IP,MAIN_DB,DB_PASSWORD,USE_DB)){
    //echo "connnection error!\n";
		$to_client['state_log'] .= "connect mysql fail...\n";

	}
	else{
		$to_client['state_log'] .= "connect mysql success!\n";
	}

	$user_leave = new MapzipUserLeave($conn);
	$user_leave->setTargetId($value['user_id']);

// delete review image dir
	if($user_leave->deleteReviewImageDir(REVIEW_TYPE_DELETE_ONEMAP,$value['map_id'])){
		$to_client['state_log'] .= "directory delete complete\n";
	}else{
		$to_client['state_log'] .= "directory delete fail\n";
		$to_client['state'] = LEAVE_ERROR_IGNORE;
	}

// delete review data 
	if($user_leave->deleteFromReviewTable(REVIEW_TYPE_DELETE_ONEMAP,$value['map_id'])){
		$to_client['state_log'] .= "delete review table success";
		$sql = "SELECT count(*) as new_total_review FROM ".REVIEW_TABLE." WHERE user_id = '{$value['user_id']}'";
		if(!$result = mysqli_query($conn,$sql)){
			$to_client['state_log'] .= $sql;
		}else{
			$row = mysqli_fetch_assoc($result);
			$sql = "UPDATE ".USER_TABLE." SET total_review = {$row['new_total_review']} WHERE userid = '{$value['user_id']}'";
			if(!mysqli_query($conn,$sql)){
				$to_client['state_log'] .= $sql;
			}else{
				$to_client['state'] .= "update success";
			}
		}
	}else{
		$to_client['state_log'] .= "delete review table something wrong";
	}


	echo json_encode($to_client);
}



?>