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

$sql = "DELETE FROM ".REVIEW_TABLE." WHERE pid = {$value['store_id']}";
if(!mysqli_query($conn,$sql)){
		// insert fail
	$to_client['state'] = $sql;
}else{
	$to_client['state'] = "review delete complete";
	$target_dir_name = "./client_{$value['user_id']}_{$value['map_id']}_{$value['store_id']}"; 
	$to_client['rmdir_state'] = removeDirectory($target_dir_name);
}
echo json_encode($to_client);
?>