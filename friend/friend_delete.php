<?php
include("../fmysql.php");
include("../mapzip-mysql-define.php");
include("../mapzip-state-define.php");
include("../user-leave-module.php");

$value = json_decode(file_get_contents('php://input'), true);

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

?>