<?php
include("../fmysql.php");
include("../mapzip-state-define.php");
include("../mapzip-mysql-define.php");

$value = json_decode(file_get_contents('php://input'), true);

if(!$conn = connect_mysqli(MYSQL_IP,MAIN_DB,DB_PASSWORD,USE_DB)){
	//echo "connnection error!\n";
	$to_client['state']=DB_CONNECTION_ERROR;
}
else{
//echo "connect success!\n";
}

$sql = "UPDATE ".REVIEW_TABLE."{$value['user_id']} SET map_id = {$value['map_id']}, review_emotion = {$value['review_emotion']}, review_text = '{$value['review_text']}' WHERE pid = {$value['store_id']}";

if(!mysqli_query($conn,$sql)){
		// insert fail
	$to_client['state'] = $sql;
}else{
	$to_client['state'] = "complete";
}
echo json_encode($to_client);

?>