<?php
include("../fmysql.php");
include("../mapzip-mysql-define.php");
include("../mapzip-state-define.php");
include("../user-leave-module.php");
include("client_rmdir.php");

$value = json_decode(file_get_contents('php://input'), true);

$to_client['state'] = NON_KNOWN_ERROR;
    
if(!$conn = connect_mysqli(MYSQL_IP,MAIN_DB,DB_PASSWORD,USE_DB)){
    //echo "connnection error!\n";
    $to_client['state_log'] .= "connect mysql fail...\n";
    
}
else{
    $to_client['state_log'] .= "connect mysql success!\n";
}


$sql = "SELECT * FROM ".REVIEW_TABLE." WHERE user_id = '{$value['user_id']}' and map_id = {$value['map_id']}";
if(!$result = mysqli_query($conn,$sql)){
    $to_client['state_log'] .= "select query error\n";
}else{
    while($row = mysqli_fetch_assoc($result)){
        $direct_path = "../client_data/client_{$value['user_id']}/store_{$row['pid']}";
        if(removeDirectory($direct_path)){
            $to_client['state_log'] .= "{$direct_path} directory delete complete\n";
        }else{
            $to_client['state_log'] .= "{$direct_path} directory delete fail\n";
            $to_client['state'] = LEAVE_ERROR_IGNORE;
        }
    }
}

$sql = "DELETE FROM ".REVIEW_TABLE." WHERE user_id = '{$value['user_id']}' and map_id = {$value['map_id']}";
if(!mysqli_query($conn,$sql)){
	$to_client['state_log'] .= $sql;
}else{
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
}
echo json_encode($to_client);

?>