<?php
include("../fmysql.php");
include("../mapzip-state-define.php");
include("../mapzip-mysql-define.php");
include("./client_rmdir.php");
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

	$sql = "SELECT pid, image_num FROM ".REVIEW_TABLE." WHERE user_id = '{$value['user_id']}' and pid = {$value['store_id']}";
	if(!$result = mysqli_query($conn,$sql)){
		$to_client->setFields("state", SQL_QUERY_ERROR);
		$to_client->setDebugs("select review_table query error", $sql);
	}else{
		$row = mysqli_fetch_assoc($result);
		$image_num = $row['image_num'];
		$to_client->setDebugs("image_num", $image_num);
	}

	if($user_leave->deleteFromReviewTable(REVIEW_TYPE_DELETE_ONEREVIEW,$value['store_id'])){
		$to_client->setFields("state", CLIENT_REVIEW_DATA_DELETE_SUCCESS);
		
		$sql = "UPDATE ".USER_TABLE. " SET total_review = total_review - 1 WHERE userid = '{$value['user_id']}'";
		if(!mysqli_query($conn,$sql)){
			$to_client->setDebugs("total_review_update", 'fail');
		}else{
			$to_client->setDebugs("total_review_update", 'success');
		}

		if($image_num!=0){
			if($user_leave->deleteReviewImageDir(REVIEW_TYPE_DELETE_ONEREVIEW,$value['store_id'])){
				$to_client->setDebugs("rmdir_state", "success, ".CLIENT_REVIEW_IMAGE_RMDIR_SUCCESS);
			}else{
				$to_client->setDebugs("rmdir_state", "fail, ".CLIENT_REVIEW_IMAGE_RMDIR_FAIL);
			}
		}else{			
			$to_client->setDebugs("rmdir_state", "none, ".CLIENT_REVIEW_IMAGE_RMDIR_NONE);
		}
	}else{
		$to_client->setFields("state", CLIENT_REVIEW_DATA_DELETE_FAIL);
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

	$user_leave = new MapzipUserLeave($conn);
	$user_leave->setTargetId($value['user_id']);

	$sql = "SELECT pid, image_num FROM ".REVIEW_TABLE." WHERE user_id = '{$value['user_id']}' and pid = {$value['store_id']}";
	if(!$result = mysqli_query($conn,$sql)){
		$to_client['state'] = SQL_QUERY_ERROR;
	}else{
		$row = mysqli_fetch_assoc($result);
		$image_num = $row['image_num'];
	}

	if($user_leave->deleteFromReviewTable(REVIEW_TYPE_DELETE_ONEREVIEW,$value['store_id'])){
		$to_client['state'] = CLIENT_REVIEW_DATA_DELETE_SUCCESS;
		$sql = "UPDATE ".USER_TABLE. " SET total_review = total_review - 1 WHERE userid = '{$value['user_id']}'";
		if(!mysqli_query($conn,$sql)){
			$to_client['total_review_state'] = "fail";
		}else{
			$to_client['total_review_state'] = "success";
		}

		if($image_num!=0){
			if($user_leave->deleteReviewImageDir(REVIEW_TYPE_DELETE_ONEREVIEW,$value['store_id'])){
				$to_client['rmdir_state'] = CLIENT_REVIEW_IMAGE_RMDIR_SUCCESS;
			}else{
				$to_client['rmdir_state'] = CLIENT_REVIEW_IMAGE_RMDIR_FAIL;
			}
		}else{			
			$to_client['rmdir_state'] = CLIENT_REVIEW_IMAGE_RMDIR_NONE;
		}
	}else{
		$to_client['state'] = CLIENT_REVIEW_DATA_DELETE_FAIL;
	}


	

	echo json_encode($to_client);
}


?>