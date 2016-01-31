<?php
include("../fmysql.php");
include("../mapzip-mysql-define.php");
include("../mapzip-state-define.php");
include("client_rmdir.php");

    
$value = json_decode(file_get_contents('php://input'), true);

$to_client['state_log'] = "";
$to_client['state'] = NON_KNOWN_ERROR;
    
    if(!$conn = connect_mysqli(MYSQL_IP,MAIN_DB,DB_PASSWORD,USE_DB)){
    //echo "connnection error!\n";
        $to_client['state_log'] .= "connect mysql fail...\n";
        $to_client['state'] = LEAVE_FAIL_SERIOUS;
    }
    else{
        $to_client['state_log'] .= "connect mysql success!\n";
    }
    
    // 특정 아이디 관련 테이블들만 삭제
    $to_client['state_log'] .= "target delete\n";
    $to_client['state_log'] .= "userid = {$value['target_id']}\n";
    
    $sql = "DELETE FROM ".USER_TABLE." WHERE userid = '{$value['target_id']}' LIMIT 1";
    if($result = mysqli_query($conn,$sql)){
        $to_client['state_log'] .= "SUCCESS : delete {$value['target_id']} from user_info table ..\n";
    }
    else{
        $to_client['state_log'] .= "FAIL : delete {$value['target_id']} from user_info table ..\n";
        $to_client['state'] = LEAVE_FAIL_SERIOUS;
    }
    $sql = "DELETE FROM ".GCM_TABLE." WHERE user_id = '{$value['target_id']}'";
    if(mysqli_query($conn,$sql)){
    	$to_client['state_log'] .= "SUCCESS : delete {$value['target_id']} from gcm_table table ..\n";
    }else{
    	$to_client['state_log'] .= $sql;
    	$to_client['state'] = LEAVE_FAIL_SERIOUS;
    }

    $sql = "DELETE FROM ".FRIEND_TABLE." WHERE from_id = '{$value['target_id']}' or to_id = '{$value['target_id']}'";
    if(mysqli_query($conn,$sql)){
        $to_client['state_log'] .= "SUCCESS : delete {$value['target_id']} from friend_table table ..\n";
    }else{
        $to_client['state_log'] .= $sql;
        $to_client['state'] = LEAVE_FAIL_SERIOUS;
    }
    
    $sql = "DELETE FROM ".CLIENT_TABLE." WHERE user_id = '{$value['target_id']}'";
    if($result = mysqli_query($conn,$sql)){
    //echo "query fail...\n";
        $to_client['state_log'] .= "SUCCESS : delete row {$value['target_id']} from mz_client...\n";
    }
    else{
        $to_client['state_log'] .= "FAIL : delete row {$value['target_id']} from mz_client..\n";
        $to_client['state'] = LEAVE_FAIL_SERIOUS;
    }
    
    $sql = "SELECT * FROM ".REVIEW_TABLE." WHERE user_id = '{$value['target_id']}'";
    if(!$result = mysqli_query($conn,$sql)){
        $to_client['state_log'] .= "select query error\n";
    }else{
        while($row = mysqli_fetch_assoc($result)){
            $direct_path = "../client_data/client_{$value['target_id']}_{$row['map_id']}_{$row['pid']}";
            if(removeDirectory($direct_path)){
                $to_client['state_log'] .= "{$direct_path} directory delete complete\n";
            }else{
                $to_client['state_log'] .= "{$direct_path} directory delete fail\n";
                $to_client['state'] = LEAVE_ERROR_IGNORE;
            }
        }
    }
    
    $sql = "DELETE FROM ".REVIEW_TABLE." WHERE user_id = '{$value['target_id']}'";
    if($result = mysqli_query($conn,$sql)){
    //echo "query fail...\n";
        $to_client['state_log'] .= "SUCCESS : delete row {$value['target_id']} from mz_review...\n";
        if(($to_client['state'] != LEAVE_FAIL_SERIOUS) || ($to_client['state'] != LEAVE_ERROR_IGNORE)){
        	$to_client['state'] = LEAVE_ALL_SUCCESS;
        }
    }
    else{
        $to_client['state_log'] .= "FAIL : delete row {$value['target_id']} from mz_review..\n";
        $to_client['state'] = LEAVE_FAIL_SERIOUS;
    }
    
    echo(json_encode($to_client));

    

?>