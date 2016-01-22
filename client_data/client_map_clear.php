<?php
include("../fmysql.php");
include("../mapzip-mysql-define.php");
include("../mapzip-state-define.php");
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
$sql = "DELETE FROM ".REVIEW_TABLE."{$value['user_id']} WHERE map_id = '{$value['map_id']}'";
if(!mysqli_query($conn,$sql)){
	$to_client['state_log'] .= $sql;
}else{
	$to_client['state_log'] = "delete review table success";
	$sql = "SELECT count(*) as new_total_review FROM ".REVIEW_TABLE.$value['user_id'];
	if(!$result = mysqli_query($conn,$sql)){
		$to_client['state_log'] .= $sql;
	}else{
		$row = mysqli_fetch_assoc($result);
		$sql = "UPDATE ".USER_TABLE." SET total_review = {$row['new_total_review']} WHERE userid = '{$value['user_id']}'";
		if(!mysqli_query($conn,$sql)){
			$to_client['state_log'] .= $sql;
		}else{
			$to_client['state'] = "update success";
		}
	}
}
echo json_encode($to_client);

?>