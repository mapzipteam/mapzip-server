<?php

include("../fmysql.php");
include("../mapzip-mysql-define.php");
include("../mapzip-state-define.php");

$value = json_decode(file_get_contents('php://input'), true);

if(!$conn = connect_mysqli(MYSQL_IP,MAIN_DB,DB_PASSWORD,USE_DB)){
	//echo "connnection error!\n";
	$to_client['state']=DB_CONNECTION_ERROR;
}
else{
//echo "connect success!\n";
}

$sql = "INSERT INTO ".CLIENT_TABLE.$value['userid']." ( type, friend_id, created) VALUES (2,'{$value['friend_id']}',now())";

if(!$result = mysqli_query($conn,$sql)){
	//echo "query fail...\n";
	$to_client['state']=$sql;
}
else{
	$to_client['state']="insert success";
}

echo json_encode($to_client);



?>