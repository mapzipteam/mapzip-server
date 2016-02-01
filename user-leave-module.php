<?php
class MapzipUserLeave{

	var $target_id = "";
	var $conn = 0;
	var $response = array();

	function MapzipUserLeave($db_connection){
		
		$target_id = "";
		$this->conn = $db_connection;
	}

	function setTargetId($id){
		$this->target_id = $id;
	}

	function execute(){
		$conn = $this->conn;
		$response = $this->response;
		$target_id = $this->target_id;
		
		$sql = "DELETE FROM ".USER_TABLE." WHERE userid = '{$target_id}' LIMIT 1";
    	if($result = mysqli_query($conn,$sql)){
        	$response['state_log'] .= "SUCCESS : delete {$target_id} from user_info table ..\n";
    	}
    	else{
        	$response['state_log'] .= "FAIL : delete {$target_id} from user_info table ..\n";
        	$response['state'] = LEAVE_FAIL_SERIOUS;
    	}
    	
    	$sql = "DELETE FROM ".GCM_TABLE." WHERE user_id = '{$target_id}'";
    	if(mysqli_query($conn,$sql)){
    		$response['state_log'] .= "SUCCESS : delete {$target_id} from gcm_table table ..\n";
    	}else{
    		$response['state_log'] .= $sql;
    		$response['state'] = LEAVE_FAIL_SERIOUS;
    	}

    	$sql = "DELETE FROM ".FRIEND_TABLE." WHERE from_id = '{$target_id}' or to_id = '{$target_id}'";
    	if(mysqli_query($conn,$sql)){
        	$response['state_log'] .= "SUCCESS : delete {$target_id} from friend_table table ..\n";
    	}else{
        	$response['state_log'] .= $sql;
        	$response['state'] = LEAVE_FAIL_SERIOUS;
    	}
    
    	$sql = "DELETE FROM ".CLIENT_TABLE." WHERE user_id = '{$target_id}'";
    	if($result = mysqli_query($conn,$sql)){
    		//echo "query fail...\n";
        	$response['state_log'] .= "SUCCESS : delete row {$target_id} from mz_client...\n";
    	}
    	else{
        	$response['state_log'] .= "FAIL : delete row {$target_id} from mz_client..\n";
        	$response['state'] = LEAVE_FAIL_SERIOUS;
    	}
    
    	$sql = "SELECT * FROM ".REVIEW_TABLE." WHERE user_id = '{$target_id}'";
   	 	if(!$result = mysqli_query($conn,$sql)){
        	$response['state_log'] .= "select query error\n";
    	}else{
        	while($row = mysqli_fetch_assoc($result)){
            	$direct_path = "../client_data/client_{$target_id}_{$row['map_id']}_{$row['pid']}";
            	if(removeDirectory($direct_path)){
                	$response['state_log'] .= "{$direct_path} directory delete complete\n";
            	}else{
                	$response['state_log'] .= "{$direct_path} directory delete fail\n";
               		$response['state'] = LEAVE_ERROR_IGNORE;
            	}
        	}
    	}
    
    	$sql = "DELETE FROM ".REVIEW_TABLE." WHERE user_id = '{$target_id}'";
    		if($result = mysqli_query($conn,$sql)){
    		//echo(arg1)ho "query fail...\n";
        	$response['state_log'] .= "SUCCESS : delete row {$target_id} from mz_review...\n";
        	if(($response['state'] != LEAVE_FAIL_SERIOUS) || ($$response['state'] != LEAVE_ERROR_IGNORE)){
        		$response['state'] = LEAVE_ALL_SUCCESS;
       		}
    	}
    	else{
        	$response['state_log'] .= "FAIL : delete row {$target_id} from mz_review..\n";
        	$response['state'] = LEAVE_FAIL_SERIOUS;
    	}

    	return $response;
	}

}
?>