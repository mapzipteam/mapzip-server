<?php
include("../fmysql.php");
include("../mapzip-state-define.php");
include("../mapzip-mysql-define.php");
include("./client_rmdir.php");

$value = json_decode(file_get_contents('php://input'), true);

if(!$conn = connect_mysqli(MYSQL_IP,MAIN_DB,DB_PASSWORD,USE_DB)){
	//echo "connnection error!\n";
	$to_client['state']=DB_CONNECTION_ERROR;
}
else{
//echo "connect success!\n";
}
$sql = "SELECT pid, image_num FROM ".REVIEW_TABLE." WHERE user_id = '{$value['user_id']}' and pid = {$value['store_id']}";
if(!$result = mysqli_query($conn,$sql)){
	$to_client['state'] = SQL_QUERY_ERROR;
}else{
	$row = mysqli_fetch_assoc($result);
	$image_num = $row['image_num'];
}

$sql = "DELETE FROM ".REVIEW_TABLE." WHERE user_id = '{$value['user_id']}' and pid = {$value['store_id']}";
if(!mysqli_query($conn,$sql)){
		// insert fail
	$to_client['state'] = CLIENT_REVIEW_DATA_DELETE_FAIL;
}else{
	$to_client['state'] = CLIENT_REVIEW_DATA_DELETE_SUCCESS;
	$sql = "UPDATE ".USER_TABLE. " SET total_review = total_review - 1 WHERE userid = '{$value['user_id']}'";
	if(!mysqli_query($conn,$sql)){
		$to_client['total_review_state'] = "fail";
	}else{
		$to_client['total_review_state'] = "success";
	}
	
	if($image_num!=0){
		$target_dir_name = "./client_{$value['user_id']}_{$value['map_id']}_{$value['store_id']}";
		if(removeDirectory($target_dir_name)){	
			$to_client['rmdir_state'] = CLIENT_REVIEW_IMAGE_RMDIR_SUCCESS;
			//$to_client['rmdir_state'] = $temp;
		}else{
			$to_client['rmdir_state'] = CLIENT_REVIEW_IMAGE_RMDIR_FAIL;
			//$to_client['rmdir_state'] = $temp;
		}
	}else{			
		$to_client['rmdir_state'] = CLIENT_REVIEW_IMAGE_RMDIR_NONE;
	}
}
	

echo json_encode($to_client);
?>