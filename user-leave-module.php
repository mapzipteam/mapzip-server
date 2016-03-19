<?php
/*
Required include file : mapzip-mysql-define, mapzip-state-define

Usage example #1 : Delete all info of one user in mapzip
    
    $user_leave = new MapzipUserLeave($conn);
    $user_leave->setTargetId($value['target_id']);
    $to_client = $user_leave->execute();

Usage example #2 : Delete only friend Info
    user_leave = new MapzipUserLeave($conn);
    $user_leave->setTargetId($value['user_id']);
    if($user_leave->deleteFromFriendTable(FRIEND_TYPE_DELETE_ONLYTOID,$value['target_id'])){
        $to_client['state_log'] .= "delete success";
    }else{
        $to_client['state_log'] .= "delete something fail";
    }

Usage example #3 : Delete Review Image Dir
    $user_leave = new MapzipUserLeave($conn);
    $user_leave->setTargetId($value['user_id']);

    if($user_leave->deleteReviewImageDir(REVIEW_TYPE_DELETE_ONEMAP,$value['map_id'])){
        $to_client['state_log'] .= "{$direct_path} directory delete complete\n";
    }else if(){
        $to_client['state_log'] .= "{$direct_path} directory delete fail\n";
        $to_client['state'] = LEAVE_ERROR_IGNORE;
    }

*/
class MapzipUserLeave{

	private $target_id = "";
	private $conn = 0;
	private $response;

	function MapzipUserLeave($db_connection, $to_client){
		
		$target_id = "";
		$this->conn = $db_connection;
        $this->response = $to_client;
	}
    function setTargetId($id){
		$this->target_id = $id;
	}
    function deleteFromUserTable(){
        $conn = $this->conn;
        $target_id = $this->target_id;
        
        $sql = "DELETE FROM ".USER_TABLE." WHERE userid = '{$target_id}' LIMIT 1";
        if($result = mysqli_query($conn,$sql)){
            return 1;
        }
        else{
            return 0;
        }
    }
    function deleteFromGCMTable(){
        $conn = $this->conn;
        $target_id = $this->target_id;

        $sql = "DELETE FROM ".GCM_TABLE." WHERE user_id = '{$target_id}'";
        if(mysqli_query($conn,$sql)){
            return 1;
        }else{
            return 0;
        }
    }
    function deleteFromFriendTable($type, $to_id = ""){
        $conn = $this->conn;
        $target_id = $this->target_id;

        if($type === FRIEND_TYPE_USER_LEAVE){
            $sql = "DELETE FROM ".FRIEND_TABLE." WHERE from_id = '{$target_id}' or to_id = '{$target_id}'";
            if(mysqli_query($conn,$sql)){
                return 1;
            }else{
                return 0;
            }
        }else if($type === FRIEND_TYPE_DELETE_ONLYTOID){
            $sql = "DELETE FROM ".FRIEND_TABLE." WHERE from_id = '{$target_id}' and to_id = '{$to_id}' LIMIT 1";
            if(mysqli_query($conn,$sql)){
                return 1;
            }else{
                return 0;
            }
        }
    }
    function deleteFromClientTable(){
        $conn = $this->conn;
        $target_id = $this->target_id;
            
        $sql = "DELETE FROM ".CLIENT_TABLE." WHERE user_id = '{$target_id}'";
        if($result = mysqli_query($conn,$sql)){
            //echo "query fail...\n";
            return 1;
        }
        else{
            return 0;
        }
    }
    /*
        Delete Review Image Directory Success : return 1
        Delete Review Image Directory Something wrong : return 0
        Delete Review Image DIrectory select error : return -1   
    */
    function deleteReviewImageDir($type, $to_id = ""){
        $conn = $this->conn;
        $target_id = $this->target_id;
        $bool = 1;
        if($type == REVIEW_TYPE_USER_LEAVE){
            $sql = "SELECT * FROM ".REVIEW_TABLE." WHERE user_id = '{$target_id}'";
            if(!$result = mysqli_query($conn,$sql)){
                return 0;
            }else{
                while($row = mysqli_fetch_assoc($result)){
                    $direct_path = "../client_data/client_{$target_id}";
                    if(removeDirectory($direct_path)){
                        //$bool = 1;
                    }else{
                        $bool = 0;
                    }
                }
            }
        }else if($type == REVIEW_TYPE_DELETE_ONEMAP){
            $sql = "SELECT * FROM ".REVIEW_TABLE." WHERE user_id = '{$target_id}' and map_id = {$to_id}";
            if(!$result = mysqli_query($conn,$sql)){
                return -1;
            }else{
                while($row = mysqli_fetch_assoc($result)){
                    $direct_path = "../client_data/client_{$target_id}/store_{$row['pid']}";
                    if(removeDirectory($direct_path)){
                        //$to_client['state_log'] .= "{$direct_path} directory delete complete\n";
                        return 1;
                    }else{
                        //$to_client['state_log'] .= "{$direct_path} directory delete fail\n";
                        //$to_client['state'] = LEAVE_ERROR_IGNORE;
                        return 0;
                    }
                }
            }
        }else if($type == REVIEW_TYPE_DELETE_ONEREVIEW){
            $direct_path = "../client_data/client_{$target_id}/store_{$to_id}";
            if(removeDirectory($direct_path)){
                //$to_client['state_log'] .= "{$direct_path} directory delete complete\n";
                return 1;
            }else{
                //$to_client['state_log'] .= "{$direct_path} directory delete fail\n";
                //$to_client['state'] = LEAVE_ERROR_IGNORE;
                return 0;
            }
        }
        
        return $bool;
    }
    /*
        @params : type
        Delete Review Data Success : return 1
        Delete Review Data Something wrong : return 0
    */
    function deleteFromReviewTable($type, $to_id = ""){
        $conn = $this->conn;
        $target_id = $this->target_id;
        if($type == REVIEW_TYPE_USER_LEAVE){
            $sql = "DELETE FROM ".REVIEW_TABLE." WHERE user_id = '{$target_id}'";
            if($result = mysqli_query($conn,$sql)){
                //echo(arg1)ho "query fail...\n";
                return 1;
            }
            else{
                return 0;
            }
        }else if($type == REVIEW_TYPE_DELETE_ONEMAP){
            $sql = "DELETE FROM ".REVIEW_TABLE." WHERE user_id = '{$target_id}' and map_id = {$to_id}";
            if(mysqli_query($conn,$sql)){
                return 1;
            }else{
                return 0;
            }
        }else if($type == REVIEW_TYPE_DELETE_ONEREVIEW){
            $sql = "DELETE FROM ".REVIEW_TABLE." WHERE user_id = '{$target_id}' and pid = {$to_id} LIMIT 1";
            if(mysqli_query($conn,$sql)){
                return 1;
            }else{
                return 0;
            }
        }
        
    }
    function execute(){
		$conn = $this->conn;
		$response = $this->response;
		$target_id = $this->target_id;
        $response = $this->response;
        $temp_state;
		
		if($this->deleteFromUserTable()){
            $response->setDebugs('delete {$target_id} from user_info table', 'success');
    	}
    	else{
            $response->setDebugs('delete {$target_id} from user_info table', 'fail');
        	$temp_state = LEAVE_FAIL_SERIOUS;
    	}

    	//$sql = "DELETE FROM ".GCM_TABLE." WHERE user_id = '{$target_id}'";
    	if($this->deleteFromGCMTable()){
            $response->setDebugs('delete {$target_id} from gcm_table table', 'success');
    		
    	}else{
            $response->setDebugs('delete {$target_id} from gcm_table table', 'fail');
    		$response['state_log'] .= $sql;
    		$temp_state = LEAVE_FAIL_SERIOUS;
    	}

    	//$sql = "DELETE FROM ".FRIEND_TABLE." WHERE from_id = '{$target_id}' or to_id = '{$target_id}'";
    	if($this->deleteFromFriendTable(FRIEND_TYPE_USER_LEAVE)){
            $response->setDebugs('delete {$target_id} from friend_table table', 'success');
    	}else{
            $response->setDebugs('delete {$target_id} from friend_table table', 'fail');
        	$temp_state = LEAVE_FAIL_SERIOUS;
    	}
    
    	//$sql = "DELETE FROM ".CLIENT_TABLE." WHERE user_id = '{$target_id}'";
    	if($this->deleteFromClientTable()){
    		//echo "query fail...\n";
            $response->setDebugs('delete row {$target_id} from mz_client', 'success');
    	}
    	else{
            $response->setDebugs('delete row {$target_id} from mz_client', 'fail');
        	$temp_state = LEAVE_FAIL_SERIOUS;
    	}

        if($this->deleteReviewImageDir(REVIEW_TYPE_USER_LEAVE)){
            $response->setDebugs('image directory delete complete', 'success');
        }else{
            $response->setDebugs('image directory delete complete', 'fail');
            if($temp_state != LEAVE_FAIL_SERIOUS){
                $temp_state = LEAVE_ERROR_IGNORE;
            }
            
        }
    
    	//$sql = "DELETE FROM ".REVIEW_TABLE." WHERE user_id = '{$target_id}'";
    	if($this->deleteFromReviewTable(REVIEW_TYPE_USER_LEAVE)){
    		$response->setDebugs('delete row {$target_id} from mz_review', 'success');
        	if(($temp_state != LEAVE_FAIL_SERIOUS) || ($temp_state != LEAVE_ERROR_IGNORE)){
        		$temp_state = LEAVE_ALL_SUCCESS;
       		}
    	}
    	else{
            $response->setDebugs('delete row {$target_id} from mz_review', 'fail');
        	$temp_state = LEAVE_FAIL_SERIOUS;
    	}
        $response->setFields('state', $temp_state);
	}

}
?>